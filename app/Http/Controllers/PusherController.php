<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class PusherController extends Controller
{

    public function index(Request $request)
    {
    }

    public function show($id,Request $request)
    {
    
    }
    
    public function store(Request $request)
    {
             
    }
    
    public function create(Request $request)
    {
        
    }
    
    public function edit($id_equipo,Request $request)
    {
          
    }
    
    public function update(Request $request, $id)
    {
        
    }
    
    public function destroy(Request $request)
    {
      
    }

    public function sendNotification()
    {
        //Remember to change this with your cluster name.
        $options = array(
            'cluster' => 'us2', 
            'encrypted' => true
        );
 
       //Remember to set your credentials below.
        $pusher = new Pusher(
            '35d7000cdead31eac2d5',
            'f9266f41e94bbc025f3e',
            '599255',
            $options
        );
        
        $message= "HOLA GONZALO CENTENO ZAPATA";
        
        //Send a message to notify channel with an event name of notify-event
        $pusher->trigger('notify', 'notify-event', $message);  
    }
    
}