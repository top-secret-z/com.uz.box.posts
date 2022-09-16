<?php
namespace wbb\system\condition;
use wcf\data\DatabaseObjectList;
use wcf\data\user\UserList;
use wcf\system\condition\AbstractCheckboxCondition;
use wcf\system\condition\IObjectListCondition;

/**
 * Condition implementation to exclude boards without users' count.
 * Do not use for other purposes!
 * 
 * @author		2018-2022 Zaydowicz.de
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.box.posts
 */
class UzBoxPostsCountCondition extends AbstractCheckboxCondition implements IObjectListCondition {
	/**
	 * @inheritDoc
	 */
	protected $fieldName = 'uzboxPostsCount';
	
	/**
	 * @inheritDoc
	 */
	protected $label = 'wcf.acp.box.uzposts.condition.board.count';
	
	/**
	 * @inheritDoc
	 */
	public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData) {
		if (!($objectList instanceof UserList)) {
			throw new \InvalidArgumentException("Object list is no instance of '".UserList::class."', instance of '".get_class($objectList)."' given.");
		}
		
		// do nothing
	}
}
