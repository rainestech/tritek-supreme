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
    protected $nextPage;
    protected $nextPage1;
    protected $nextPage2;
    protected $nextPage3;

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

        $people = BasecampFacade::people();

        $this->nextPage = 1;

        for ($i = 0; $i < 1000; $i++) {
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

        $this->nextPage = 1;

//        foreach ($projects->index() as $project) {
//            if (!$proj = Projects::where('bcId', $project->id)->first()) {
//                $proj = new Projects();
//            }
//
//            $proj->bcId = $project->id;
//            $proj->status = $project->status;
//            $proj->name = $project->name;
//            $proj->description = $project->description;
//            $proj->startDate = Carbon::parse($project->created_at);
//
//            if (Str::contains(strtolower($proj->name), 'completed')) {
//                $proj->status = "completed";
//                $proj->completedDate =  Carbon::parse($project->updated_at);
//            }
//
//            if (Str::contains(strtolower($proj->name), 'halted')) {
//                $proj->status = "halted";
//            }
//
//            $proj->save();
//        }

        while($this->nextPage) {
            foreach ($projects->index($this->nextPage) as $project) {
                if (!$proj = Projects::where('bcId', $project->id)->first()) {
                    $proj = new Projects();
                }

                $proj->bcId = $project->id;
                $proj->status = $project->status;
                $proj->name = $project->name;
                $proj->description = $project->description;
                $proj->startDate = Carbon::parse($project->created_at);

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
            $this->nextPage = $projects->index($this->nextPage)->nextPage();
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
                $this->nextPage1 = 1;

                while ($this->nextPage1) {
                    foreach (BasecampFacade::campfires($record->bcId)->index($this->nextPage1) as $campfire) {
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
                    $this->nextPage1 = BasecampFacade::campfires($record->bcId)->index($this->nextPage1)->nextPage();
                }
            }
        });
    }
}
