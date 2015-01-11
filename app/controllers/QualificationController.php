<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class QualificationController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for qualification
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Qualification", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $qualification = Qualification::find($parameters);
        if (count($qualification) == 0) {
            $this->flash->notice("The search did not find any qualification");

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $qualification,
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
     * Edits a qualification
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $qualification = Qualification::findFirstByid($id);
            if (!$qualification) {
                $this->flash->error("qualification was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "qualification",
                    "action" => "index"
                ));
            }

            $this->view->id = $qualification->id;

            $this->tag->setDefault("id", $qualification->id);
            $this->tag->setDefault("name", $qualification->name);
            
        }
    }

    /**
     * Creates a new qualification
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "index"
            ));
        }

        $qualification = new Qualification();

        $qualification->name = $this->request->getPost("name");
        

        if (!$qualification->save()) {
            foreach ($qualification->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "new"
            ));
        }

        $this->flash->success("qualification was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "qualification",
            "action" => "index"
        ));

    }

    /**
     * Saves a qualification edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $qualification = Qualification::findFirstByid($id);
        if (!$qualification) {
            $this->flash->error("qualification does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "index"
            ));
        }

        $qualification->name = $this->request->getPost("name");
        

        if (!$qualification->save()) {

            foreach ($qualification->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "edit",
                "params" => array($qualification->id)
            ));
        }

        $this->flash->success("qualification was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "qualification",
            "action" => "index"
        ));

    }

    /**
     * Deletes a qualification
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $qualification = Qualification::findFirstByid($id);
        if (!$qualification) {
            $this->flash->error("qualification was not found");

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "index"
            ));
        }

        if (!$qualification->delete()) {

            foreach ($qualification->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "qualification",
                "action" => "search"
            ));
        }

        $this->flash->success("qualification was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "qualification",
            "action" => "index"
        ));
    }

}
