<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class QuestionCategoryDescriptionController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for question_category_description
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "QuestionCategoryDescription", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $question_category_description = QuestionCategoryDescription::find($parameters);
        if (count($question_category_description) == 0) {
            $this->flash->notice("The search did not find any question_category_description");

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $question_category_description,
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
     * Edits a question_category_description
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $question_category_description = QuestionCategoryDescription::findFirstByid($id);
            if (!$question_category_description) {
                $this->flash->error("question_category_description was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "question_category_description",
                    "action" => "index"
                ));
            }

            $this->view->id = $question_category_description->id;

            $this->tag->setDefault("id", $question_category_description->id);
            $this->tag->setDefault("question_category_id", $question_category_description->question_category_id);
            $this->tag->setDefault("language_id", $question_category_description->language_id);
            $this->tag->setDefault("name", $question_category_description->name);
            
        }
    }

    /**
     * Creates a new question_category_description
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "index"
            ));
        }

        $question_category_description = new QuestionCategoryDescription();

        $question_category_description->question_category_id = $this->request->getPost("question_category_id");
        $question_category_description->language_id = $this->request->getPost("language_id");
        $question_category_description->name = $this->request->getPost("name");
        

        if (!$question_category_description->save()) {
            foreach ($question_category_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "new"
            ));
        }

        $this->flash->success("question_category_description was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category_description",
            "action" => "index"
        ));

    }

    /**
     * Saves a question_category_description edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $question_category_description = QuestionCategoryDescription::findFirstByid($id);
        if (!$question_category_description) {
            $this->flash->error("question_category_description does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "index"
            ));
        }

        $question_category_description->question_category_id = $this->request->getPost("question_category_id");
        $question_category_description->language_id = $this->request->getPost("language_id");
        $question_category_description->name = $this->request->getPost("name");
        

        if (!$question_category_description->save()) {

            foreach ($question_category_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "edit",
                "params" => array($question_category_description->id)
            ));
        }

        $this->flash->success("question_category_description was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category_description",
            "action" => "index"
        ));

    }

    /**
     * Deletes a question_category_description
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $question_category_description = QuestionCategoryDescription::findFirstByid($id);
        if (!$question_category_description) {
            $this->flash->error("question_category_description was not found");

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "index"
            ));
        }

        if (!$question_category_description->delete()) {

            foreach ($question_category_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question_category_description",
                "action" => "search"
            ));
        }

        $this->flash->success("question_category_description was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question_category_description",
            "action" => "index"
        ));
    }

}
