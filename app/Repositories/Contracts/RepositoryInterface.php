<?php

namespace App\Repositories\Contracts;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

interface RepositoryInterface {
    /**
     * Find all records that match a given conditions
     * @param array $condition
     * @return mixed
     */
    public function find(array $condition = []);

    /**
     * Find a specific record that matches a given conditions
     * @param array $condition
     * @return mixed
     */
    public function findOne(array $condition);

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id);


    /**
     * Create a record
     * @param array $attribute
     * @return mixed
     */
    public function create(array $attribute);

    /**
     * Create a record
     * @param array $attribute
     * @return mixed
     */
    public function createMany(array $attribute);


    /**
     * @param array $attribute
     * @return mixed
     */
    public function update(array $attribute = []);


    /** Save a given record
     * @return mixed
     */
    public function save();

    /**
     * Delete the record from the database
     *
     * @return bool
     *
     * @throws Exception
     */
    public function delete();

    /**
     * get records from database
     * @param $query
     * @return mixed
     */
    public function get($query);

    /**
     * Delete array record from the database
     * @param array $ids
     * @return mixed
     */
    public function destroy(array $ids);

    /**
     * Find amount record the matches a given conditions
     * @param array $conditions
     * @return mixed
     */
    public function findCount(array $conditions);


    /**
     * Update multiple record
     * @param Builder $query
     * @param array $attributes
     * @return mixed
     */
    public function updateMultiple(Builder $query, array $attributes);


    /**
     * create new record or update existing record
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values);


    /**
     * Find all existing record
     * @return mixed
     */
    public function findAll();


    /**
     * Find record by list of id
     * @param array $ids
     * @return mixed
     */
    public function findByIds(array $ids);


    /**
     * Get Model
     *
     * @return Model
     */
    public function model();

    /**
     * Make new Model
     *
     * @return Model
     */
    public function makeModel();

    /**
     * Reset model query
     *
     * @return void
     */
    public function resetModel();

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $column
     * @param array $attribute
     * @param $options
     * @return mixed
     */
    public function deleteWhere($column, array $attribute, $options);

    /**
     * @param $column
     * @param array $attribute
     * @param array $data
     * @param $options
     * @return mixed
     */
    public function updateWhereIn($column, array $attribute, array $data, $options);

    /**
     * @param $id
     * @return mixed
     */
    public function findModelById($id);

    /**
     * @param array $values
     * @param array $uniqueBy
     * @param array $update
     * @return mixed
     */
    public function insertOrUpdateMultiple(array $values, array $uniqueBy, array $update);

    /**
     * @param $column
     * @param array $attribute
     * @param array $data
     * @param $option
     * @return mixed
     */
    public function updateWhereNotIn($column, array $attribute, array $data, $option);

    /**
     * @param $column
     * @param array $attribute
     * @param $option
     * @return mixed
     */
    public function deleteWhereNotIn($column, array $attribute, $option);
}
