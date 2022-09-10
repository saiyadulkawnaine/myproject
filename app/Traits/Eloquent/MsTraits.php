<?php
 
namespace App\Traits\Eloquent;
 
trait MsTraits
{
	
	/**
	 * Get all model.
	 *
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function get()
	{
	    return $this->model->get();
	    
	}
 
	/**
	 * Get model by id.
	 *
	 * @param integer $id
	 *
	 * @return App\model
	 */
	public function find($id)
    {
        return $this->model->find($id);
    }
	
	/**
	 * Get model by attributes.
	 *
	 * @param array $attributes
	 *
	 * @return App\model
	 */
	public function where(array $attributes)
	{
		return  $this->model->where($attributes);
	}
	
	
 
	/**
	 * Create a new model.
	 *
	 * @param array $attributes
	 *
	 *@return Illuminate\Database\Eloquent\Collection
	 */
	public function create(array $attributes)
	{
		return $this->model->create($attributes);
	}
 
	/**
	 * Update a model.
	 *
	 * @param integer $id
	 * @param array $attributes
	 *
	 * @return App\model
	 */
	public function update($id, array $attributes)
	{
		return $this->model->find($id)->update($attributes);
	}
 
	/**
	 * Delete a model.
	 *
	 * @param integer $id
	 *
	 * @return boolean
	 */
	public function delete($id)
	{
		return $this->model->find($id)->delete();
	}
	
	public function __call($method, $args)
    {
        return call_user_func_array([$this->model, $method], $args);
    }
}