<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserAnswerController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user_answer
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "UserAnswer", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $user_answer = UserAnswer::find($parameters);
        if (count($user_answer) == 0) {
            $this->flash->notice("The search did not find any user_answer");

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $user_answer,
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
     * Edits a user_answer
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user_answer = UserAnswer::findFirstByid($id);
            if (!$user_answer) {
                $this->flash->error("user_answer was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "user_answer",
                    "action" => "index"
                ));
            }

            $this->view->id = $user_answer->id;

            $this->tag->setDefault("id", $user_answer->id);
            $this->tag->setDefault("user_id", $user_answer->user_id);
            $this->tag->setDefault("answer_id", $user_answer->answer_id);
            
        }
    }

    /**
     * Creates a new user_answer
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "index"
            ));
        }

        $user_answer = new UserAnswer();

        $user_answer->user_id = $this->request->getPost("user_id");
        $user_answer->answer_id = $this->request->getPost("answer_id");
        

        if (!$user_answer->save()) {
            foreach ($user_answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "new"
            ));
        }

        $this->flash->success("user_answer was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_answer",
            "action" => "index"
        ));

    }

    /**
     * Saves a user_answer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user_answer = UserAnswer::findFirstByid($id);
        if (!$user_answer) {
            $this->flash->error("user_answer does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "index"
            ));
        }

        $user_answer->user_id = $this->request->getPost("user_id");
        $user_answer->answer_id = $this->request->getPost("answer_id");
        

        if (!$user_answer->save()) {

            foreach ($user_answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "edit",
                "params" => array($user_answer->id)
            ));
        }

        $this->flash->success("user_answer was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_answer",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user_answer
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user_answer = UserAnswer::findFirstByid($id);
        if (!$user_answer) {
            $this->flash->error("user_answer was not found");

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "index"
            ));
        }

        if (!$user_answer->delete()) {

            foreach ($user_answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_answer",
                "action" => "search"
            ));
        }

        $this->flash->success("user_answer was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_answer",
            "action" => "index"
        ));
    }

}
