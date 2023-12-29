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
use wcf\system\condition\AbstractTextCondition;
use wcf\system\condition\IObjectListCondition;

/**
 * Condition implementation for lastActivityTime.
 */
class UzBoxPostsLastActivityCondition extends AbstractTextCondition implements IObjectListCondition
{
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
    protected function getFieldElement(): string
    {
        return '<input type="number" name="' . $this->fieldName . '" value="' . $this->fieldValue . '" class="tiny" min="1">';
    }

    /**
     * @inheritDoc
     */
    public function readFormParameters(): void
    {
        if (isset($_POST[$this->fieldName])) {
            $this->fieldValue = (int)$_POST[$this->fieldName];
        }
    }

    /**
     * @inheritDoc
     */
    public function addObjectListCondition(DatabaseObjectList $objectList, array $conditionData): void
    {
        if (!($objectList instanceof UserList)) {
            throw new InvalidArgumentException(
                "Object list is no instance of '" . UserList::class . "', "
                . "instance of '" . \get_class($objectList) . "' given."
            );
        }

        // do nothing
    }
}
