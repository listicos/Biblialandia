<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class QuestionErrorController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for question_error
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "QuestionError", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $question_error = QuestionError::find($parameters);
        if (count($question_error) == 0) {
            $this->flash->notice("The search did not find any question_error");

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $question_error,
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
     * Edits a question_error
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $question_error = QuestionError::findFirstByid($id);
            if (!$question_error) {
                $this->flash->error("question_error was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "question_error",
                    "action" => "index"
                ));
            }

            $this->view->id = $question_error->id;

            $this->tag->setDefault("id", $question_error->id);
            $this->tag->setDefault("name", $question_error->name);
            
        }
    }

    /**
     * Creates a new question_error
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "index"
            ));
        }

        $question_error = new QuestionError();

        $question_error->name = $this->request->getPost("name");
        

        if (!$question_error->save()) {
            foreach ($question_error->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "new"
            ));
        }

        $this->flash->success("question_error was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_error",
            "action" => "index"
        ));

    }

    /**
     * Saves a question_error edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $question_error = QuestionError::findFirstByid($id);
        if (!$question_error) {
            $this->flash->error("question_error does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "index"
            ));
        }

        $question_error->name = $this->request->getPost("name");
        

        if (!$question_error->save()) {

            foreach ($question_error->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "edit",
                "params" => array($question_error->id)
            ));
        }

        $this->flash->success("question_error was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_error",
            "action" => "index"
        ));

    }

    /**
     * Deletes a question_error
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $question_error = QuestionError::findFirstByid($id);
        if (!$question_error) {
            $this->flash->error("question_error was not found");

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "index"
            ));
        }

        if (!$question_error->delete()) {

            foreach ($question_error->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_error",
                "action" => "search"
            ));
        }

        $this->flash->success("question_error was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_error",
            "action" => "index"
        ));
    }

}
