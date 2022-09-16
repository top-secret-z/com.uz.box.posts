<?php
namespace wbb\system\condition;
use wcf\data\user\UserProfileList;
use wcf\data\DatabaseObjectList;
use wcf\system\condition\AbstractSelectCondition;
use wcf\system\condition\IObjectListCondition;
use wcf\util\StringUtil;

/**
 * Condition implementation for the period.
 * Do not use for other purposes!
 * 
 * @author		2018-2022 Zaydowicz.de
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.box.posts
 */
class UzBoxPostsPeriodCondition extends AbstractSelectCondition implements IObjectListCondition {
	/**
	 * @inheritDoc
	 */
	protected $description = 'wcf.acp.box.uzposts.condition.period.description';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldName = 'uzboxPostsPeriod';
	
	/**
	 * @inheritDoc
	 */
	protected $label = 'wcf.acp.box.uzposts.condition.period';
	
	/**
	 * @inheritDoc
	 */
	protected $fieldValue = 'month';
	
	/**
	 * @inheritDoc
	 */
	public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData) {
		if (!($objectList instanceof UserProfileList)) {
			throw new \InvalidArgumentException("Object list is no instance of '".UserProfileList::class."', instance of '".get_class($objectList)."' given.");
		}
		
		// do nothing
	}
	
	/**
	 * @inheritDoc
	 */
	protected function getOptions() {
		return [
				'alltime' => 'wcf.acp.box.uzposts.condition.period.alltime',
				'curday' => 'wcf.acp.box.uzposts.condition.period.curday',
				'curweek' => 'wcf.acp.box.uzposts.condition.period.curweek',
				'curmonth' => 'wcf.acp.box.uzposts.condition.period.curmonth',
				'curyear' => 'wcf.acp.box.uzposts.condition.period.curyear',
				'day' => 'wcf.acp.box.uzposts.condition.period.day',
				'week' => 'wcf.acp.box.uzposts.condition.period.week',
				'month' => 'wcf.acp.box.uzposts.condition.period.month',
				'year' => 'wcf.acp.box.uzposts.condition.period.year'
		];
	}
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		if (isset($_POST[$this->fieldName])) $this->fieldValue = StringUtil::trim($_POST[$this->fieldName]);
	}
}
