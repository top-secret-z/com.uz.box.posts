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
use wcf\data\user\UserList;
use wcf\system\condition\AbstractCheckboxCondition;
use wcf\system\condition\IObjectListCondition;

/**
 * Condition implementation to exclude boards without users' count.
 */
class UzBoxPostsCountCondition extends AbstractCheckboxCondition implements IObjectListCondition
{
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
    public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData)
    {
        if (!($objectList instanceof UserList)) {
            throw new InvalidArgumentException("Object list is no instance of '" . UserList::class . "', instance of '" . \get_class($objectList) . "' given.");
        }

        // do nothing
    }
}
