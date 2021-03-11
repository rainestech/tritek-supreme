<?php


namespace App\Models;

use Belvedere\Basecamp\BasecampFacade;
use Carbon\Carbon;
use Rainestech\Personnel\Entity\Candidates;
use Rainestech\Personnel\Entity\CandidatesProjects;
use Rainestech\Personnel\Entity\Projects;
use Str;

class MigrateCandidateData
{
    protected $config;

    public function __construct(Array $config)
    {
        $this->config = $config;
    }

    public function migrateProfile() {
        BasecampFacade::init([
            'id' => $this->config['id'],
            'href' => $this->config['href'],
            'token' => $this->config['token'],
            'refresh_token' => $this->config['refresh_token'],
        ]);

        $sample = Candidates::orderBy('bcPage', 'desc')->first();
        clock($sample);

        $k = $sample ? $sample->bcPage + 1 : 1;

        $people = BasecampFacade::people();

        for ($i = $k; $i < 1000; $i++) {
            $page = $people->index($i);
            if ($page->count() < 1) {
                break;
            }

            foreach ($page as $person) {
                if (!$cand = Candidates::where('email', $person->email_address)->first()) {
                    $cand = new Candidates();
                }

                $cand->email = $person->email_address;
                $cand->name = $person->name;
                $cand->title = $person->title;
                $cand->avatar = $person->avatar_url;
                $cand->description = $person->bio;
                $cand->bcId = $person->id;
                $cand->bcPage = $i;

                $cand->save();
                sleep(1);
            }
        }

        return $this;
    }

    public function migrateProjects() {
        BasecampFacade::init([
            'id' => $this->config['id'],
            'href' => $this->config['href'],
            'token' => $this->config['token'],
            'refresh_token' => $this->config['refresh_token'],
        ]);

        $projects = BasecampFacade::projects();

        for ($i = 1; $i < 1000; $i++) {
            $page = $projects->index($i);
            if ($page->count() < 1) {
                break;
            }

            foreach ($page as $project) {
                if (!$proj = Projects::where('bcId', $project->id)->first()) {
                    $proj = new Projects();
                }

                $proj->bcId = $project->id;
                $proj->status = $project->status;
                $proj->name = $project->name;
                $proj->description = $project->description;
                $proj->startDate = Carbon::parse($project->created_at);
                $proj->bcPage = $i;

                if (Str::contains(strtolower($proj->name), 'completed')) {
                    $proj->status = "completed";
                    $proj->completedDate =  Carbon::parse($project->updated_at);
                }

                if (Str::contains(strtolower($proj->name), 'halted')) {
                    $proj->status = "halted";
                }

                $proj->save();

                sleep(1);
            }
        }

        return $this;
    }

    public function attachProjects() {
        BasecampFacade::init([
            'id' => $this->config['id'],
            'href' => $this->config['href'],
            'token' => $this->config['token'],
            'refresh_token' => $this->config['refresh_token'],
        ]);

        Projects::chunk(100, function($records){

            foreach($records as $record) {

                for ($i = 0; $i < 1000; $i++) {
                    $page = BasecampFacade::campfires($record->bcId)->index($i);
                    if ($page->count() < 1) {
                        break;
                    }
                    foreach ($page as $campfire) {
                        if (!CandidatesProjects::where('bccId', $campfire->creator->id)->exists()) {
                            $candidate = Candidates::where('bcId', $campfire->creator->id)->first();

                            if ($candidate) {
                                $candPro = new CandidatesProjects();
                                $candPro->pId = $record->id;
                                $candPro->cId = $candidate->id;
                                $candPro->bccId = $campfire->creator->id;
                                $candPro->bcpId = $record->bcId;

                                $candPro->save();
                            }
                        }
                    }
                }
            }
        });
    }
}
