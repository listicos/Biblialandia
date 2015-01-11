<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AnswerController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for answer
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Answer", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $answer = Answer::find($parameters);
        if (count($answer) == 0) {
            $this->flash->notice("The search did not find any answer");

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $answer,
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
     * Edits a answer
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $answer = Answer::findFirstByid($id);
            if (!$answer) {
                $this->flash->error("answer was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "answer",
                    "action" => "index"
                ));
            }

            $this->view->id = $answer->id;

            $this->tag->setDefault("id", $answer->id);
            $this->tag->setDefault("question_id", $answer->question_id);
            $this->tag->setDefault("answer", $answer->answer);
            $this->tag->setDefault("correct", $answer->correct);
            $this->tag->setDefault("status", $answer->status);
            $this->tag->setDefault("created_at", $answer->created_at);
            $this->tag->setDefault("updated_at", $answer->updated_at);
            
        }
    }

    /**
     * Creates a new answer
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "index"
            ));
        }

        $answer = new Answer();

        $answer->question_id = $this->request->getPost("question_id");
        $answer->answer = $this->request->getPost("answer");
        $answer->correct = $this->request->getPost("correct");
        $answer->status = $this->request->getPost("status");
        $answer->created_at = $this->request->getPost("created_at");
        $answer->updated_at = $this->request->getPost("updated_at");
        

        if (!$answer->save()) {
            foreach ($answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "new"
            ));
        }

        $this->flash->success("answer was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "answer",
            "action" => "index"
        ));

    }

    /**
     * Saves a answer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $answer = Answer::findFirstByid($id);
        if (!$answer) {
            $this->flash->error("answer does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "index"
            ));
        }

        $answer->question_id = $this->request->getPost("question_id");
        $answer->answer = $this->request->getPost("answer");
        $answer->correct = $this->request->getPost("correct");
        $answer->status = $this->request->getPost("status");
        $answer->created_at = $this->request->getPost("created_at");
        $answer->updated_at = $this->request->getPost("updated_at");
        

        if (!$answer->save()) {

            foreach ($answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "edit",
                "params" => array($answer->id)
            ));
        }

        $this->flash->success("answer was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "answer",
            "action" => "index"
        ));

    }

    /**
     * Deletes a answer
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $answer = Answer::findFirstByid($id);
        if (!$answer) {
            $this->flash->error("answer was not found");

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "index"
            ));
        }

        if (!$answer->delete()) {

            foreach ($answer->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "answer",
                "action" => "search"
            ));
        }

        $this->flash->success("answer was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "answer",
            "action" => "index"
        ));
    }

}
