<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Guard;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::with('owner_guard', 'relieving_guard')->get();
        $guards = Guard::all();

        return view('permissions', compact('permissions', 'guards'));
        /*return response()->json([
            'permission' =>  $permissions
        ]);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create-permisson');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'guard_id' => 'required',
            'reason' => 'required',
            'date_time' => 'required'
        ]);

        $permission = new Permission();

        $permission->guard_id = $request->guard_id;
	
		if($request->reliever == null){
			 $permission->reliever = null;     
		}else{
			 $permission->reliever = $request->reliever;
		}
        
        $permission->reason = $request->reason;
        $permission->date = $request->date_time;

        if($permission->save()){
            return response()->json([
                'error' => false,
                'data' => $permission,
                'message' => 'Permission Created Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating permission'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $permission = new Permission();

        return view('edit-permission')->with('permission', $permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'guard_id' => 'required',
            'reliever' => 'required',
            'reason' => 'required',
            'date' => 'required'
        ]);

        $permission->guard_id = $request->guard_id;
        $permission->reliever = $request->guard_id;
        $permission->reason = $request->reason;
        $permission->date = $request->date;

        if($permission->update()){
            return response()->json([
                'error' => false,
                'data' => $permission,
                'message' => 'Permission Updated Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating permission'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }

    /**
     * ---------------------
     * Approve permission
     * ---------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approval(Request $request)
    {
        $request->validate([
            'permission_id' => 'required',
            'approval' => 'required'
        ]);

        $permission = Permission::where('id', $request->permission_id)->first();
        $permission->approval = $request->approval;

        if($permission->save()){
            return response()->json([
                'error' => false,
                'data' => $permission,
                'message' => 'Permission Approved Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error approving permission'
            ]);
        }
    }

    /**
     * -------------------------------------------------------
     * Approving guard permission and adding a reliever guard
     * -------------------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reliever(Request $request)
    {
        $request->validate([
            'permission_id' => 'required', 
            'reliever' => 'required', 
        ]);

        $permission = Permission::where('id', $request->permission_id)->first();

        $permission->approval = 1;

        $permission->reliever = $request->reliever;

        $guard = Guard::where('id', $request->reliever)->first();

        if($permission->save()){
            return response()->json([
                'error' => false,
                'message' => 'Permission approved successfully',
                'guard' => $guard
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Permission could not be approved'
            ]);
        }
        
    }
}
