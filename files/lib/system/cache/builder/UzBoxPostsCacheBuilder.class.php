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

namespace wbb\system\cache\builder;

use wbb\data\board\BoardList;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Caches the users with most posts iaw conditions.
 */
class UzBoxPostsCacheBuilder extends AbstractCacheBuilder
{
    /**
     * @inheritDoc
     */
    protected $maxLifetime = 180;

    /**
     * @inheritDoc
     *
     * @throws DatabaseQueryExecutionException
     * @throws DatabaseQueryException
     * @throws SystemException
     */
    protected function rebuild(array $parameters): array
    {
        /**
         * preset data
         */
        $sqlLimit = $value = $uzboxPostsLast = 0;
        $period = '';

        $conditionBuilder = new PreparedStatementConditionBuilder();

        foreach ($parameters as $condition) {
            if (isset($condition['limit'])) {
                $sqlLimit = $condition['limit'];
            }

            if (isset($condition['uzboxPostsPeriod'])) {
                $period = $condition['uzboxPostsPeriod'];
            }

            if (isset($condition['uzboxPostsPeriodValue'])) {
                $value = (int)$condition['uzboxPostsPeriodValue'];
            }

            if (isset($condition['uzboxPostsLast'])) {
                $uzboxPostsLast = $condition['uzboxPostsLast'];
            }

            if (isset($condition['isDeleted'])) {
                $conditionBuilder->add('post_table.isDeleted = ?', [$condition['isDeleted']]);
            }

            if (isset($condition['isDisabled'])) {
                $conditionBuilder->add('post_table.isDisabled = ?', [$condition['isDisabled']]);
            }
            if (isset($condition['isEnabled'])) {
                $conditionBuilder->add('post_table.isDisabled = ?', [0]);
            }

            if (isset($condition['isClosed'])) {
                $conditionBuilder->add('post_table.isClosed = ?', [$condition['isClosed']]);
            }

            if (isset($condition['wbbThreadBoardIDs'])) {
                $conditionBuilder->add('post_table.threadID IN (SELECT threadID FROM wbb' . WCF_N . '_thread WHERE boardID IN (?))', [$condition['wbbThreadBoardIDs']]);
            }

            if (isset($condition['uzboxPostsCount'])) {
                $boardList = new BoardList();
                $boardList->getConditionBuilder()->add('board.countUserPosts = 0');
                $boardList->readObjectIDs();
                $boardIDs = $boardList->getObjectIDs();
                if (\count($boardIDs)) {
                    $conditionBuilder->add('post_table.threadID IN (SELECT threadID FROM wbb' . WCF_N . '_thread WHERE boardID NOT IN (?))', [$boardIDs]);
                }
            }

            if (isset($condition['lastActivity'])) {
                $conditionBuilder->add('user_table.lastActivityTime > ?', [TIME_NOW - $condition['lastActivity'] * 86400]);
            }

            if (isset($condition['userIsBanned'])) {
                $conditionBuilder->add('user_table.banned = ?', [$condition['userIsBanned']]);
            }

            if (isset($condition['userIsEnabled'])) {
                if ((int)$condition['userIsEnabled'] === 0) {
                    $conditionBuilder->add('user_table.activationCode > ?', [0]);
                }

                if ((int)$condition['userIsEnabled'] === 1) {
                    $conditionBuilder->add('user_table.activationCode = ?', [0]);
                }
            }

            if (isset($condition['groupIDs'])) {
                $conditionBuilder->add('user_table.userID IN (SELECT userID FROM wcf' . WCF_N . '_user_to_group WHERE groupID IN (?))', [$condition['groupIDs']]);
            }

            if (isset($condition['notGroupIDs'])) {
                $conditionBuilder->add('user_table.userID NOT IN (SELECT userID FROM wcf' . WCF_N . '_user_to_group WHERE groupID IN (?))', [$condition['notGroupIDs']]);
            }
        }

        // period
        $start = $start2 = -1;
        $end = TIME_NOW;

        switch ($period) {
            case 'curday':
                $start = \strtotime("midnight", TIME_NOW) - ($value - 1) * 86400;

                if ($start < 0) {
                    $start = 0;
                }

                $start2 = $start - $value * 86400;
                if ($start2 < 0) {
                    $start2 = 0;
                }

                break;

            case 'curweek':
                $start = \strtotime("monday this week");
                $start -= ($value - 1) * 86400 * 7;

                if ($start < 0) {
                    $start = 0;
                }

                $start2 = $start - $value * 86400 * 7;

                if ($start2 < 0) {
                    $start2 = 0;
                }

                break;

            case 'curmonth':
                $month = \date('n');
                $year = \date('Y');
                $savedValue = $value;

                while ($value > 1) {
                    $month--;

                    if ((int)$month === 0) {
                        $month = 12;
                        $year--;
                    }

                    $value--;
                }

                if ($year < 1970) {
                    $year = 1970;
                }

                $start = \strtotime("{$year}-{$month}-01 00:00:01");

                while ($savedValue > 0) {
                    $month--;

                    if ((int)$month === 0) {
                        $month = 12;
                        $year--;
                    }

                    $savedValue--;
                }

                if ($year < 1970) {
                    $year = 1970;
                }

                $start2 = \strtotime("{$year}-{$month}-01 00:00:01");

                break;

            case 'curyear':
                $year = \date('Y') - ($value - 1);

                if ($year < 1970) {
                    $year = 1970;
                }

                $start = \strtotime("{$year}-01-01 00:00:01");

                $year -= $value;

                if ($year < 1970) {
                    $year = 1970;
                }

                $start2 = \strtotime("{$year}-01-01 00:00:01");

                break;

            case 'day':
                $start = TIME_NOW - $value * 86400;

                if ($start < 0) {
                    $start = 0;
                }

                $start2 = $start - $value * 86400;

                if ($start2 < 0) {
                    $start2 = 0;
                }

                break;

            case 'week':
                $start = TIME_NOW - $value * 86400 * 7;

                if ($start < 0) {
                    $start = 0;
                }

                $start2 = $start - $value * 86400 * 7;

                if ($start2 < 0) {
                    $start2 = 0;
                }

                break;

            case 'month':
                if ($value > 500) {
                    $value = 500;
                }

                $string = '-' . $value . ' month';
                $start = \strtotime($string, $end);

                $value = 2 * $value;

                if ($value > 500) {
                    $value = 500;
                }

                $string = '-' . $value . ' month';
                $start2 = \strtotime($string, $start);

                break;

            case 'year':
                if ($value > 47) {
                    $value = 47;
                }

                $string = '-' . $value . ' year';
                $start = \strtotime($string, $end);

                $value = 2 * $value;

                if ($value > 47) {
                    $value = 47;
                }

                $string = '-' . $value . ' month';
                $start2 = \strtotime($string, $start);

                break;
        }

        // clone condition builder
        $conditionBuilderClone = clone $conditionBuilder;

        if ($start > -1) {
            $conditionBuilder->add('post_table.time BETWEEN ? AND ?', [$start, $end]);
        }

        // get userIDs and posts
        $userIDs = $userToPost = [];
        $sql = "SELECT post_table.userID as postUserID, COUNT(post_table.userID) as uzboxPosts
                FROM wcf1_user user_table
                LEFT JOIN wbb1_post as post_table ON (post_table.userID = user_table.userID)
                " . $conditionBuilder . "
                GROUP BY postUserID
                ORDER BY uzboxPosts DESC";

        $statement = WCF::getDB()->prepare($sql, $sqlLimit);
        $statement->execute($conditionBuilder->getParameters());

        while ($row = $statement->fetchArray()) {
            $userIDs[] = $row['postUserID'];
            $userToPost[$row['postUserID']] = $row['uzboxPosts'];
        }

        $users = [];

        if (!empty($userIDs)) {
            foreach ($userIDs as $userID) {
                $user = UserProfileRuntimeCache::getInstance()->getObject($userID);

                if (null !== $user) {
                    $users[] = [
                        'user' => $user,
                        'posts' => $userToPost[$user->userID],
                    ];
                }
            }
        }

        // last
        $lasts = [];

        if ($uzboxPostsLast) {
            $conditionBuilder = $conditionBuilderClone;

            if ($start2 > -1) {
                $conditionBuilder->add('post_table.time BETWEEN ? AND ?', [$start2, $start]);
            }

            $userIDs = $userToPost = [];
            $sql = "SELECT post_table.userID as postUserID, COUNT(post_table.userID) as uzboxPosts
                    FROM wcf1_user user_table
                    LEFT JOIN wbb1_post as post_table ON (post_table.userID = user_table.userID)
                    " . $conditionBuilder . "
                    GROUP BY postUserID
                    ORDER BY uzboxPosts DESC";

            $statement = WCF::getDB()->prepare($sql, $uzboxPostsLast);
            $statement->execute($conditionBuilder->getParameters());

            while ($row = $statement->fetchArray()) {
                $userIDs[] = $row['postUserID'];
                $userToPost[$row['postUserID']] = $row['uzboxPosts'];
            }

            if (!empty($userIDs)) {
                foreach ($userIDs as $userID) {
                    $user = new UserProfile(new User($userID));

                    $lasts[] = [
                        'user' => $user,
                        'posts' => $userToPost[$user->userID],
                    ];
                }
            }
        }

        return [
            'users' => $users,
            'lasts' => $lasts,
        ];
    }
}
