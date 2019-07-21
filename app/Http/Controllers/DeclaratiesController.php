<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Declaratie;
use App\DeclaratieDeelname;
use App\Lid;
use App\Boete;
use App\SErekening;
use DB;
use Illuminate\Support\Facades\Auth;


class DeclaratiesController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->lid_id;
        $declaraties = Declaratie::select('declaratie.declaratie_id','betaald_door_id','datum','declaratie.bedrag','omschrijving','created_by_id')->join('declaratie_deelname', function($join) use ($user_id){
            $join->on('declaratie.declaratie_id','declaratie_deelname.declaratie_id');
        })->orWhere(function ($query) use ($user_id){
        $query->orWhere('declaratie.created_by_id', '=', $user_id)
            ->orWhere('declaratie.betaald_door_id', '=', $user_id)
            ->orWhere('declaratie_deelname.lid_id', '=', $user_id);
        })->groupBy('declaratie.declaratie_id')->orderBy('datum', 'desc')->paginate(10);
        return view('declaraties/declaraties', ['declaraties' => $declaraties]);
    }

    public function declaratie($id)
    {
        $declaratie = Declaratie::find($id);
        $leden_deelname = Lid::join('declaratie_deelname', 'lid.lid_id', '=', 'declaratie_deelname.lid_id')->where('declaratie_deelname.declaratie_id', '=', $id)->get();

        return view('declaraties/declaratie', ['declaratie' => $declaratie, 'leden_deelname' => $leden_deelname]);
    }

    public function toevoegen()
    {
        $leden = Lid::select('lid_id', 'roepnaam', 'achternaam')->where('type_lid', '!=', 'Geen')->orderBy('type_lid', 'asc')->get();
        return view('declaraties/declaratie_toevoegen', ['leden' => $leden]);
    }

    public function wijzigen($id)
    {
        $declaratie = Declaratie::find($id);
        $leden = Lid::select('lid_id', 'roepnaam', 'achternaam')->where('type_lid', '!=', 'Geen')->orderBy('type_lid', 'asc')->get();

        $leden_deelname = Lid::select('lid.lid_id', 'roepnaam', 'achternaam','declaratie_deelname.lid_id as deelname','type_lid')->leftJoin('declaratie_deelname', function($join) use ($id){
            $join->on('lid.lid_id','declaratie_deelname.lid_id');
            $join->where('declaratie_deelname.declaratie_id',$id);
        })->where('type_lid','!=','Geen')->orderBy('type_lid','asc')->get();
        return view('declaraties/declaratie_wijzigen', ['declaratie' => $declaratie, 'leden_deelname' => $leden_deelname, 'leden'=>$leden]);
    }

    public function insert_update_declaratie(Request $request)
    {
        $validatedData = $request->validate([
            'datum' => 'required|date',
            'bedrag' => 'required|numeric|min:0.00',
            'betaald_door_id' => 'required|numeric',
            'omschrijving' => 'required|max:100000',
            'deelnemers'=> 'required']);

        if(isset($request->declaratie_id)){
            $declaratie = Declaratie::find($request->declaratie_id);
            $this->remove_declaratie_deelname($declaratie);
        }else{
            $declaratie = new Declaratie;
        }

        $declaratie->datum = $request->datum;
        $declaratie->bedrag = $request->bedrag;
        $declaratie->betaald_door_id = $request->betaald_door_id;
        $declaratie->omschrijving = $request->omschrijving;
        $declaratie->created_by_id = Auth::user()->lid_id;
        $declaratie->save();

        subtract_verschuldigd($declaratie->betaald_door_id, $declaratie->bedrag);

        $this->add_declaratie_deelname($request->deelnemers, $declaratie);

        return redirect('/declaratie/' . $declaratie->declaratie_id);
    }


    // Adds declaratie deelname per lid, also adds costs
    public function add_declaratie_deelname($deelnemers, $declaratie){
        $bedragen = divide_money($declaratie->bedrag, count($deelnemers));
        $i = 1;
        foreach ($deelnemers as $lid) {
            add_verschuldigd($lid, $bedragen[$i]);
            $declaratie_deelname = new DeclaratieDeelname;
            $declaratie_deelname->lid_id = $lid;
            $declaratie_deelname->declaratie_id = $declaratie->declaratie_id;
            $declaratie_deelname->bedrag = $bedragen[$i];
            $declaratie_deelname->save();
            $i++;
        }
    }

    // Remove declaratie deelname, remove costs
    public function remove_declaratie_deelname($declaratie){
        add_verschuldigd($declaratie->betaald_door_id, $declaratie->bedrag);
        $declaratie_id = $declaratie->declaratie_id;
        $deelnemers = Lid::select('lid.lid_id','declaratie_deelname.bedrag as bedrag')->join('declaratie_deelname', function($join) use ($declaratie_id){
            $join->on('lid.lid_id','declaratie_deelname.lid_id');
            $join->where('declaratie_deelname.declaratie_id',$declaratie_id);
        })->get();
        foreach ($deelnemers as $deelnemer){
            subtract_verschuldigd($deelnemer->lid_id, $deelnemer->bedrag);
            DeclaratieDeelname::where('lid_id',$deelnemer->lid_id)->where('declaratie_id',$declaratie_id)->delete();
        }
    }

    public function verwijderen($id){
        $declaratie = Declaratie::find($id);
        $this->remove_declaratie_deelname($declaratie);
        $declaratie->delete();
        return redirect('/declaraties');
    }

}
