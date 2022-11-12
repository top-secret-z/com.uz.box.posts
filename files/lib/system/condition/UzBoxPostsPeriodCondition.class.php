<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wbb\system\condition;

use InvalidArgumentException;
use wcf\data\DatabaseObjectList;
use wcf\data\user\UserProfileList;
use wcf\system\condition\AbstractSelectCondition;
use wcf\system\condition\IObjectListCondition;
use wcf\util\StringUtil;

/**
 * Condition implementation for the period.
 */
class UzBoxPostsPeriodCondition extends AbstractSelectCondition implements IObjectListCondition
{
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
    public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData)
    {
        if (!($objectList instanceof UserProfileList)) {
            throw new InvalidArgumentException("Object list is no instance of '" . UserProfileList::class . "', instance of '" . \get_class($objectList) . "' given.");
        }

        // do nothing
    }

    /**
     * @inheritDoc
     */
    protected function getOptions()
    {
        return [
            'alltime' => 'wcf.acp.box.uzposts.condition.period.alltime',
            'curday' => 'wcf.acp.box.uzposts.condition.period.curday',
            'curweek' => 'wcf.acp.box.uzposts.condition.period.curweek',
            'curmonth' => 'wcf.acp.box.uzposts.condition.period.curmonth',
            'curyear' => 'wcf.acp.box.uzposts.condition.period.curyear',
            'day' => 'wcf.acp.box.uzposts.condition.period.day',
            'week' => 'wcf.acp.box.uzposts.condition.period.week',
            'month' => 'wcf.acp.box.uzposts.condition.period.month',
            'year' => 'wcf.acp.box.uzposts.condition.period.year',
        ];
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters()
    {
        if (isset($_POST[$this->fieldName])) {
            $this->fieldValue = StringUtil::trim($_POST[$this->fieldName]);
        }
    }
}
