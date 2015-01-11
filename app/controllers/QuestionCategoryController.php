<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class QuestionCategoryController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for question_category
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "QuestionCategory", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $question_category = QuestionCategory::find($parameters);
        if (count($question_category) == 0) {
            $this->flash->notice("The search did not find any question_category");

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $question_category,
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
     * Edits a question_category
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $question_category = QuestionCategory::findFirstByid($id);
            if (!$question_category) {
                $this->flash->error("question_category was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "question_category",
                    "action" => "index"
                ));
            }

            $this->view->id = $question_category->id;

            $this->tag->setDefault("id", $question_category->id);
            
        }
    }

    /**
     * Creates a new question_category
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "index"
            ));
        }

        $question_category = new QuestionCategory();

        $question_category->id = $this->request->getPost("id");
        

        if (!$question_category->save()) {
            foreach ($question_category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "new"
            ));
        }

        $this->flash->success("question_category was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category",
            "action" => "index"
        ));

    }

    /**
     * Saves a question_category edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $question_category = QuestionCategory::findFirstByid($id);
        if (!$question_category) {
            $this->flash->error("question_category does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "index"
            ));
        }

        $question_category->id = $this->request->getPost("id");
        

        if (!$question_category->save()) {

            foreach ($question_category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "edit",
                "params" => array($question_category->id)
            ));
        }

        $this->flash->success("question_category was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category",
            "action" => "index"
        ));

    }

    /**
     * Deletes a question_category
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $question_category = QuestionCategory::findFirstByid($id);
        if (!$question_category) {
            $this->flash->error("question_category was not found");

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "index"
            ));
        }

        if (!$question_category->delete()) {

            foreach ($question_category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category",
                "action" => "search"
            ));
        }

        $this->flash->success("question_category was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category",
            "action" => "index"
        ));
    }

}
