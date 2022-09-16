<?php
namespace wbb\system\condition;
use wcf\data\user\UserList;
use wcf\data\DatabaseObjectList;
use wcf\system\condition\AbstractTextCondition;
use wcf\system\condition\IObjectListCondition;
use wcf\system\WCF;

/**
 * Condition implementation for lastActivityTime.
 * Do not use for other purposes!
 * 
 * @author		2018-2022 Zaydowicz.de
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.box.posts
 */
class UzBoxPostsLastActivityCondition extends AbstractTextCondition implements IObjectListCondition {
	/**
	 * @inheritDoc
	 */
	protected $description = 'wcf.acp.box.uzposts.condition.lastActivity.description';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldName = 'lastActivity';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldValue = '365';
	/**
	 * @inheritDoc
	 */
	protected $label = 'wcf.acp.box.uzposts.condition.lastActivity';
	
	/**
	 * @inheritDoc
	 */
	protected function getFieldElement() {
		return '<input type="number" name="'.$this->fieldName.'" value="'.$this->fieldValue.'" class="tiny" min="1">';
	}
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		if (isset($_POST[$this->fieldName])) $this->fieldValue = intval($_POST[$this->fieldName]);
	}
	
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
