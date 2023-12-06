<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;

class AbstractRepository implements RepositoryInterface
{
    protected Model $model;

    /**
     * AbstractRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function find(array $conditions = [])
    {
        return $this->model->where($conditions)->get();
    }

    /**
     * @inheritdoc
     */
    public function findOne(array $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    /**
     * @inheritdoc
     */
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @inheritdoc
     */
    public function findByConditionWithLimit($condition, $limit, $offset = 0) {
        return $this->model->where($condition)->offset($offset)->limit($limit)->get();
    }
    /**
     * @inheritdoc
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @inheritdoc
     */
    public function updateByCondition(array $attributes = [], array $condition = [])
    {
        $attributes['updated_at'] = Carbon::today();
        return $this->model->where($condition)->update($attributes);
    }

    /**
     * @inheritdoc
     */
    public function updateByConditionWithNoUpdatedAt(array $attributes = [], array $condition = [])
    {
        return $this->model->where($condition)->update($attributes);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->model->save();
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        return $this->model->delete();
    }

    /**
     * @inheritdoc
     */
    public function deleteRowByCondition($condition) {
        return $this->model->where($condition)->delete();
    }

    /**
     * @inheritdoc
     */
    public function get($query)
    {
        return $query->get();
    }

    /**
     * @inheritdoc
     */
    public function destroy(array $ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * @inheritdoc
     */
    public function deleteByCondition(array $condition)
    {
        return $this->model->where($condition)->delete();
    }


    /**
     * @inheritdoc
     */
    public function findCount(array $conditions)
    {
        return $this->model->where($conditions)->count();
    }

    /**
     * @inheritdoc
     */
    public function toBase($query)
    {
        return $query->toBase();
    }

    /**
     * @inheritdoc
     */
    public function updateMultiple(Builder $query, array $attributes = [])
    {
        return $query->update($attributes);
    }

    /**
     * @inheritdoc
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @inheritdoc
     */
    public function findAll($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @inheritdoc
     */
    public function findByIds(array $ids, $columns = ['*'])
    {
        return $this->model->whereIn('id', $ids)->get($columns);
    }

    /**
     * @inheritdoc
     */
    public function model()
    {
        return get_class($this->model);
    }

    /**
     * @inheritdoc
     */
    public function makeModel()
    {
        $this->model = App::make($this->model());

        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function resetModel()
    {
        return $this->makeModel();
    }

    /**
     * @inheritdoc
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @inheritdoc
     */
    public function deleteWhere($column, $attribute, $options)
    {
        return $this->model->where($options)->whereIn($column, $attribute)->delete();
    }

    /**
     * @inheritdoc
     */
    public function updateWhereIn($column, $attribute, $data, $options)
    {
        return $this->model->where($options)->whereIn($column, $attribute)->update($data);
    }

    /**
     * @inheritdoc
     */
    public function findModelById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param array $values
     * @param array $uniqueBy
     * @param array $update
     * @return mixed
     */
    public function insertOrUpdateMultiple(array $values, array $uniqueBy, array $update)
    {
        return $this->model->upsert($values, $uniqueBy, $update);
    }

    /**
     * @param array $columns
     * @param $condition
     * @return mixed
     */
    public function selectColumnByCondition(array $columns, $condition)
    {
        return $this->model->select($columns)->where($condition)->get();
    }

    /**
     * @inheritdoc
     */
    public function updateWhereNotIn($column, $attribute, $data, $option)
    {
        return $this->model->where($option)->whereNotIn($column, $attribute)->update($data);
    }

    /**
     * @inheritdoc
     */
    public function deleteWhereNotIn($column, $attribute, $option)
    {
        return $this->model->where($option)->whereNotIn($column, $attribute)->delete();
    }
    /**
     * @param array $columns
     * @param $condition
     * @return mixed
     */
    public function selectFirstByCondition(array $columns, $condition)
    {
        return $this->model->select($columns)->where($condition)->first();
    }

    /**
     * @param $conditions
     * @param $column
     * @param $arrayConditions
     * @return mixed
     */
    public function findOneWithWhereIn($conditions, $column, $arrayConditions)
    {
        return $this->model->where($conditions)->whereIn($column, $arrayConditions)->first();
    }

    /**
     * @inheritdoc
     */
    public function createMany(array $attributes)
    {
        return $this->model->insert($attributes);
    }

    /**
     * @inheritdoc
     */
    public function update(array $attributes = [])
    {
        return $this->model->update($attributes);
    }
}
