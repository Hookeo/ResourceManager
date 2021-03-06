<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use Illuminate\Http\Request;

use Response;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use File;

class WorkListController extends Controller
{
    public function generateReport()
    {
        $resources = Auth::user()->resources;
        $events = Auth::user()->events;

        if($resources->isEmpty() && $events->isEmpty()){
            $resourcesSet = false;
            return view('WorkList.generateReport', compact('resources', 'events', 'resourcesSet'));
        }
        else{
            $resourcesSet = true;
            $pdf = App::make('dompdf.wrapper');
            $view = View::make('WorkList._pdfLayout')->with('resources', $resources)->with('events', $events);
            $contents = $view->render();
            $pdf->loadHTML($contents);
            $report = $pdf->output();

            $directory = DIRECTORY_SEPARATOR . Auth::user()->email;

            Storage::disk('public')->deleteDirectory($directory);
            Storage::disk('public')->makeDirectory($directory);

            $filename = uniqid(Auth::user()->email, true) . '.pdf';
            Storage::disk('public')->put($directory. DIRECTORY_SEPARATOR . $filename, $report);
            $file = Storage::disk('public')->get($directory. DIRECTORY_SEPARATOR . $filename);

            return view('WorkList.generateReport', compact('resources', 'events', 'file', 'resourcesSet'));
        }
    }

    public function mobileReport()
    {
        $resources = Auth::user()->resources;
        $events = Auth::user()->events;

        //create the pdf
        $pdf = App::make('dompdf.wrapper');
        $view = View::make('WorkList._pdfLayout')->with('resources', $resources)->with('events', $events);
        $contents = $view->render();
        $pdf->loadHTML($contents);
        $report = $pdf->output();

        //make public directory with user email
        $directory = Auth::user()->email;
        $path = public_path('/pdf/'.$directory);

        if (!File::exists($path))
        {
           File::makeDirectory($path, 0775);
        }
        else
        {
            File::cleanDirectory($path);
        }
        //delete the contents and folder, regenerate folder
        //create unique pdf name so a new pdf is shown on mobile devices
        $filename = uniqid(Auth::user()->email, true) . '.pdf';
        $path = public_path('/pdf/'. $directory . '/' . $filename);

        //store the file and retrieve for response view
        File::put($path, $report);

        return Response::download($path);
    }

    public function emptyReport()
    {
        Auth::user()->resources()->detach();
        Auth::user()->events()->detach();
        return Redirect::back();
    }

    public function generatePDF()
    {
        $pdf = App::make('dompdf.wrapper');
        $view = View::make('WorkList._pdfLayout')->with('resources', Auth::user()->resources)->with('events', Auth::user()->events);
        $contents = $view->render();
        $pdf->loadHTML($contents);
        return $pdf->stream();
    }
}
