<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser{
	//regresar respuesta correcta al cliente
	protected function ok($data,$code){
		return response()->json(['data'=> $data],$code);
	}

	//regresar respuesta correcta al cliente paginada
	protected function okPaginate($data,$code){
		return response()->json($data,$code);
	}

	//regresar error al cliente
	protected function error($message,$code){
		return response()->json(['error'=> $message,'code'=> $code],$code);
	}
	//mostrar y enviar collecciones al cliente
	protected function showAll(Collection $collection,$code = 200,$paginate = true,$pages = 2){
		if($paginate){
			$collection = $this->paginate($collection,$pages);
			return $this->okPaginate($collection, $code);
		} 
		return $this->ok($collection, $code);
	}

	//mostrar un model al cliente
	protected function showOne(Model $intance,$code = 200){
		return $this->ok($intance, $code);
	}

	//paginacion
	protected function paginate(Collection $collection,$pages){
		$page = LengthAwarePaginator::resolveCurrentPage();
		$perPage = $pages;
		$results = $collection->slice(($page - 1) * $perPage,$perPage)->values();

		$paginated = new LengthAwarePaginator($results,$collection->count(),$perPage,$page,[
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());

		return $paginated;
	}
}