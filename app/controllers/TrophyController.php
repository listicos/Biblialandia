<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TrophyController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for trophy
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Trophy", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $trophy = Trophy::find($parameters);
        if (count($trophy) == 0) {
            $this->flash->notice("The search did not find any trophy");

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $trophy,
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
     * Edits a trophy
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $trophy = Trophy::findFirstByid($id);
            if (!$trophy) {
                $this->flash->error("trophy was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "trophy",
                    "action" => "index"
                ));
            }

            $this->view->id = $trophy->id;

            $this->tag->setDefault("id", $trophy->id);
            
        }
    }

    /**
     * Creates a new trophy
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "index"
            ));
        }

        $trophy = new Trophy();

        

        if (!$trophy->save()) {
            foreach ($trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "new"
            ));
        }

        $this->flash->success("trophy was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy",
            "action" => "index"
        ));

    }

    /**
     * Saves a trophy edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $trophy = Trophy::findFirstByid($id);
        if (!$trophy) {
            $this->flash->error("trophy does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "index"
            ));
        }

        

        if (!$trophy->save()) {

            foreach ($trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "edit",
                "params" => array($trophy->id)
            ));
        }

        $this->flash->success("trophy was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy",
            "action" => "index"
        ));

    }

    /**
     * Deletes a trophy
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $trophy = Trophy::findFirstByid($id);
        if (!$trophy) {
            $this->flash->error("trophy was not found");

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "index"
            ));
        }

        if (!$trophy->delete()) {

            foreach ($trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy",
                "action" => "search"
            ));
        }

        $this->flash->success("trophy was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy",
            "action" => "index"
        ));
    }

}
