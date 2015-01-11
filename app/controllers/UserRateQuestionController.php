<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserRateQuestionController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user_rate_question
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "UserRateQuestion", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $user_rate_question = UserRateQuestion::find($parameters);
        if (count($user_rate_question) == 0) {
            $this->flash->notice("The search did not find any user_rate_question");

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $user_rate_question,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user_rate_question
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user_rate_question = UserRateQuestion::findFirstByid($id);
            if (!$user_rate_question) {
                $this->flash->error("user_rate_question was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "user_rate_question",
                    "action" => "index"
                ));
            }

            $this->view->id = $user_rate_question->id;

            $this->tag->setDefault("id", $user_rate_question->id);
            $this->tag->setDefault("user_id", $user_rate_question->user_id);
            $this->tag->setDefault("question_id", $user_rate_question->question_id);
            $this->tag->setDefault("qualification_id", $user_rate_question->qualification_id);
            $this->tag->setDefault("question_error_id", $user_rate_question->question_error_id);
            $this->tag->setDefault("created_at", $user_rate_question->created_at);
            $this->tag->setDefault("updated_at", $user_rate_question->updated_at);
            
        }
    }

    /**
     * Creates a new user_rate_question
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "index"
            ));
        }

        $user_rate_question = new UserRateQuestion();

        $user_rate_question->user_id = $this->request->getPost("user_id");
        $user_rate_question->question_id = $this->request->getPost("question_id");
        $user_rate_question->qualification_id = $this->request->getPost("qualification_id");
        $user_rate_question->question_error_id = $this->request->getPost("question_error_id");
        $user_rate_question->created_at = $this->request->getPost("created_at");
        $user_rate_question->updated_at = $this->request->getPost("updated_at");
        

        if (!$user_rate_question->save()) {
            foreach ($user_rate_question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "new"
            ));
        }

        $this->flash->success("user_rate_question was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_rate_question",
            "action" => "index"
        ));

    }

    /**
     * Saves a user_rate_question edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user_rate_question = UserRateQuestion::findFirstByid($id);
        if (!$user_rate_question) {
            $this->flash->error("user_rate_question does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "index"
            ));
        }

        $user_rate_question->user_id = $this->request->getPost("user_id");
        $user_rate_question->question_id = $this->request->getPost("question_id");
        $user_rate_question->qualification_id = $this->request->getPost("qualification_id");
        $user_rate_question->question_error_id = $this->request->getPost("question_error_id");
        $user_rate_question->created_at = $this->request->getPost("created_at");
        $user_rate_question->updated_at = $this->request->getPost("updated_at");
        

        if (!$user_rate_question->save()) {

            foreach ($user_rate_question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "edit",
                "params" => array($user_rate_question->id)
            ));
        }

        $this->flash->success("user_rate_question was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_rate_question",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user_rate_question
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user_rate_question = UserRateQuestion::findFirstByid($id);
        if (!$user_rate_question) {
            $this->flash->error("user_rate_question was not found");

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "index"
            ));
        }

        if (!$user_rate_question->delete()) {

            foreach ($user_rate_question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_rate_question",
                "action" => "search"
            ));
        }

        $this->flash->success("user_rate_question was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_rate_question",
            "action" => "index"
        ));
    }

}
