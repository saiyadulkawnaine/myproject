<?php
 
namespace App\Repositories\Contracts;
 
interface MsRepository
{
	function get();
 
	function where(array $attributes );
	
    function find($id);	
	
	function create(array $attributes);
 
	function update($id, array $attributes);
 
	function delete($id);
}