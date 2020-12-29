<?php

namespace Statistic\Models;

use Statistic\Interfaces\ScoreDataIndexerInterface;

class Calculation implements ScoreDataIndexerInterface {

    /**
     *
     * @var array
     */
    protected $users;

    /**
     * Returns count of users having score withing the interval.
     *
     * @param int $rangeStart
     * @param int $rangeEnd
     * @return int
     */
    public function getCountOfUsersWithinScoreRange(
            int $rangeStart,
            int $rangeEnd
    ): int {

        $n = 0;

        foreach ($this->getUsers() as $user) {
            if (filter_var($user["Score"], FILTER_VALIDATE_INT, ['options' => ['min_range' => $rangeStart, 'max_range' => $rangeEnd]])) {
                $n++;
            }
        }

        return $n;
    }

    /**
     * Returns count of users meet input condition.
     *
     * @param string $region
     * @param string $gender
     * @param bool $hasLegalAge
     * @param bool $hasPositiveScore
     * @return int
     */
    public function getCountOfUsersByCondition(
            string $region,
            string $gender,
            bool $hasLegalAge,
            bool $hasPositiveScore
    ): int {
        $v = 0;

        foreach ($this->getUsers() as $user) {
            if (in_array($region, $user) && in_array($gender, $user)) {
                if ($hasLegalAge) {
                    if ($user["Age"] > 22) {
                        if ($hasPositiveScore && $user["Score"] > 0) {
                            $v++;
                        }
                    }
                } else {
                    if ($user["Age"] < 21) {
                        if (!$hasPositiveScore & $user["Score"] < 0) {
                            $v++;
                        }
                    }
                }
            }
        }
        
        return $v;
    }

    public function getUsers(): array {
        return $this->users;
    }

    public function setUsers(array $users): void {
        $this->users = $users;
    }

}
