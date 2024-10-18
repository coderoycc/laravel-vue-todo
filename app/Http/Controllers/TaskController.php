<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller{
    /**
     * Enviamos todas las tasks de un usuario, manejando filtros de status, y sort
     */
    public function get_by_user(Request $request){
        $user = auth()->user();
        if($user){
            $query = Task::where('user_id', $user->id);
            if($request->has('status')){
                $query->where('status', $request->query('status'));
                $query->orderBy('id', 'desc');
            }else if($request->query('sort')){
                $target = $request->query('sort') == 'expires' ? 'expires' : 'created_at';
                $query->orderBy($target, 'desc');
            }else
                $query->orderBy('id', 'desc');
            
            $tasks = $query->get()->all();
            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);
        }
        return $this->responseWithoutUser();
    }
    /**
     * Creamos una nueva tarea
     */
    public function create(StoreTaskRequest $req){
        $user = auth()->user();
        if($user){
            try {
                Task::create(array_merge($req->validated(), ['user_id' => $user->id]));
    
                $tasks = $this->data_callback($user->id);
                return response()->json([
                    'success' => true,
                    'data' => $tasks,
                ], 201); // 201 Created
            } catch (\Throwable $th) {
                throw new HttpException(500, $th->getMessage());
            }
            
        }
        return $this->responseWithoutUser();
    }

    /**
     * [PUT] Actualizar datos de una tarea
     */
    public function update($id, TaskUpdateRequest $req){
        $task = Task::find($id);
        $user = auth()->user();
        
        if($task){
            if($user){
                $data = $req->validated();
                $task->status = $data['status'];
                $task->update($data);
                $tasks = $this->data_callback($user->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Actualizado con exito',
                    'data' => $tasks
                ], 200);
            }
            return $this->response_without_user();
        }
        return response()->json([
            'success' => false,
            'message' => 'No existe la tarea con el ID'
        ], 404); 
    }

    /**
     * [PATCH] Cambiamos el estado de una tarea, por defecto se cambia a HECHO, en caso de que se mande un estado se pone el enviado
     */
    public function change_status($id, Request $req){
        $task = Task::find($id);
        $user = auth()->user();
        if($task){
            $data = $req->all();
            $status = $data['status'] ?? 'HECHO';
            $task->status = strtoupper($status);
            $task->save();
            $tasks = $this->data_callback($user->id);
            return response()->json([
                'success' => true,
                'data' => $tasks
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'No existe la tarea con el ID'
        ], 404);
    }


    
    /**
     * Funcion que devuelve datos tasks para refrescar el store en el front
     */
    public function data_callback($user_id):array{
        $tasks = Task::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->get()
            ->all();
        return $tasks;

    }
    
    /**
     * respuesta comun para cuando no existe usuario <<login>>
     */
    public function response_without_user(){
        return response()->json([
            'success' => false,
            'error' => 'Ocurrio un error, no existe el usuario'
        ], 401);
    }

}
