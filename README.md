EasyJoin (Aliased Version)
========
CakePHP plugin - Easily create joins for your associated models.

EasyJoin makes using ad-hoc joins simple and quick. No longer do you have to deal with unbinding/bindings model on-the-fly or writing `join` arrays to force Cake to use joins instead of doing multiple queries. EasyJoin will automatically detect the relationship between model and create joins array for you. 

This variant of `EasyJoin` contains a small hack for allowing EasyJoin to work with Containable. It only requires a small change in the code and nothing else.

# Credits

EasyJoin is made with the awesome work by Tigrang in [http://github.com/tigrang/EasyJoin](http://github.com/tigrang/EasyJoin).

EasyJoin was inspired by the article "[Restoring elegance to CakePHP — doing multiple joins The Right Way™](http://cfc.kizzx2.com/index.php/restoring-elegance-to-cakephp-doing-multiple-joins-the-right-way/)"

# Requirements
* PHP >= 5.3.0
* CakePHP >= 2.0

# Installation

_[Manual]_

1. Download this plugin with the "ZIP" option above.
2. Unzip
3. Copy the resulting folder to `app/Plugin`
4. Rename the folder to `EasyJoin`

## Usage

### AppModel

	App::uses('EasyJoinAppModel', 'EasyJoin.Model');
	class AppModel extends EasyJoinAppModel {

	}

### Example (Model Code)

	public function findByCategoryRoot($id) { // belongs in "User" model, for example purposes
		return $this->find('all', array(
			'conditions' => array('Category2.root_id' => $id),
			'joins' => array(
				self::joinLeft("Parent", true, false),
				Parent::joinLeft("SecondParent"),
				SecondParent::joinLeft("Category")
			),
			'contain' => array(
				'Parent' => array(
					'SecondParent' => array(
						'Category'
					)
				)
			)
		));
	}

For the first model, specify the third parameter with `false` so it does not use aliases for `User.parent_id = Parent2.id`

Any conditions specified on the joined Models should follow with a `2`, like `Category2`, `Parent2`, `SecondParent2`.
This is to avoid conflicts with the `Containable` behavior.