<?php
App::uses('Model', 'Model');
/**
 * EasyJoin - easily create joins for your associated models
 */
class EasyJoinAppModel extends Model {

/**
 * Left join an associated model
 *
 * @see EasyJoinAppModel::_join()
 * @static
 * @param string $association
 * @param bool|array $conditions
 * @param bool $assocAlias
 * @return array
 */
	public static function joinLeft($association, $conditions = true, $assocAlias = true) {
		return self::_join($association, $conditions, 'LEFT', $assocAlias);
	}

/**
 * Inner join an associated model
 *
 * @see EasyJoinAppModel::_join()
 * @static
 * @param string $association
 * @param bool|array $conditions
 * @param bool $assocAlias
 * @return array
 */
	public static function joinInner($association, $conditions = true, $assocAlias = true) {
		return self::_join($association, $conditions, 'INNER');
	}

/**
 * Creates a join and determines conditions based on association type
 *
 * @static
 * @param string $association
 * @param boolean|array $conditions True for automatic detection, array for custom
 * @param string $type
 * @return array
 */
	protected static function _join($association, $conditions, $type, $assocAlias = true) {
		if ($conditions === true) {
			$model = get_called_class();
			$obj = ClassRegistry::getObject($model);
			if ($obj === false) {
				$obj = ClassRegistry::init($model);
			}
			$primaryModel = $joinModel = $foreignKey = null;
			if (array_key_exists($association, $obj->belongsTo)) {
				$primaryModel = $association;
				$joinModel = $model;
				$foreignKey = $obj->belongsTo[$association]['foreignKey'];
			} elseif (array_key_exists($association, $obj->hasOne)) {
				$primaryModel = $model;
				$joinModel = $association;
				$foreignKey = $obj->hasOne[$association]['foreignKey'];
			} elseif (array_key_exists($association, $obj->hasMany)) {
				$primaryModel = $model;
				$joinModel = $association;
				$foreignKey = $obj->hasMany[$association]['foreignKey'];
			}
			if($assocAlias) {
				$conditions = sprintf('%s.id = %s2.%s', $primaryModel, $joinModel, $foreignKey);
			}
			else {
				$conditions = sprintf('%s2.id = %s.%s', $primaryModel, $joinModel, $foreignKey);
			}
		}
		return array(
			'table' => Inflector::tableize($association),
			'alias' => $association . "2",
			'type' => $type,
			'foreignKey' => false,
			'conditions' => $conditions
		);
	}

}