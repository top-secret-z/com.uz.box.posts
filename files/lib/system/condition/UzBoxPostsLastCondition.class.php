<?php
namespace wbb\system\condition;
use wcf\data\user\UserList;
use wcf\data\DatabaseObjectList;
use wcf\system\condition\AbstractTextCondition;
use wcf\system\condition\IObjectListCondition;
use wcf\system\WCF;

/**
 * Condition implementation for a last period value.
 * Do not use for other purposes!
 * 
 * @author		2018-2022 Zaydowicz.de
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.box.posts
 */
class UzBoxPostsLastCondition extends AbstractTextCondition implements IObjectListCondition {
	/**
	 * @inheritDoc
	 */
	protected $description = 'wcf.acp.box.uzposts.condition.last.description';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldName = 'uzboxPostsLast';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldValue = '1';
	/**
	 * @inheritDoc
	 */
	protected $label = 'wcf.acp.box.uzposts.condition.last';
	
	/**
	 * @inheritDoc
	 */
	protected function getFieldElement() {
		return '<input type="number" name="'.$this->fieldName.'" value="'.$this->fieldValue.'" class="tiny" min="0">';
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
