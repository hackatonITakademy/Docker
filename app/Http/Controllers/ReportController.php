<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessReport;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::all();
        return new Response($reports->toArray(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'git_repository' => 'max:191|url',
            'email' => 'email|required',
        ]);

        if ($validator->fails()) {
            return new Response($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $report = Report::where('git_repository', '=', $request->git_repository)->first();

        // todo check if url exist, check if domain name is github

        if ($report instanceof Report) {
            return $this->update($request, $report);
        }

        $report = new Report();

        $report->git_repository = $request->git_repository;
//
//        try {
//            ProcessReport::dispatch($report);
//        } catch (Exception $e) {
//            dd($e->getMessage());
//        }
//        \Storage::disk('local')->put('file.txt', 'Content idk lol mdr');
        // todo create the report get the mail with $request->mail
        ProcessReport::dispatch($report);

        $report->save();

        if ($request->user() !== null) {
            $report->users()->attach($request->user()->id);
        }

        return new Response($report->toArray(), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $report = Report::find($id)->first();


        if ($request->user() !== null) {
            $report->users()->attach($request->user()->id);
        }

        ProcessReport::dispatch($report);
        // todo create the report
        // $report->filename('test')

        return new Response($report->toArray(), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getByUser(Request $request)
    {
        $user = $request->user();
        $reports = array();
        foreach ($user->reports as $report) {
            $reports[] = $report->toArray();
        }

        return new Response($reports, Response::HTTP_OK);
    }
}
