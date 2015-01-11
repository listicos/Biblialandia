<?php

class UserRateQuestion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $question_id;

    /**
     *
     * @var integer
     */
    public $qualification_id;

    /**
     *
     * @var integer
     */
    public $question_error_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

}
