<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "User", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $user = User::find($parameters);
        if (count($user) == 0) {
            $this->flash->notice("The search did not find any user");

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $user,
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
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user = User::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "user",
                    "action" => "index"
                ));
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("lastname", $user->lastname);
            $this->tag->setDefault("facebook_id", $user->facebook_id);
            $this->tag->setDefault("facebook_token", $user->facebook_token);
            $this->tag->setDefault("google_id", $user->google_id);
            $this->tag->setDefault("created_at", $user->created_at);
            $this->tag->setDefault("updated_at", $user->updated_at);
            $this->tag->setDefault("language_id", $user->language_id);
            $this->tag->setDefault("last_session", $user->last_session);
            $this->tag->setDefault("status", $user->status);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "index"
            ));
        }

        $user = new User();

        $user->email = $this->request->getPost("email", "email");
        $user->name = $this->request->getPost("name");
        $user->lastname = $this->request->getPost("lastname");
        $user->facebook_id = $this->request->getPost("facebook_id");
        $user->facebook_token = $this->request->getPost("facebook_token");
        $user->google_id = $this->request->getPost("google_id");
        $user->created_at = $this->request->getPost("created_at");
        $user->updated_at = $this->request->getPost("updated_at");
        $user->language_id = $this->request->getPost("language_id");
        $user->last_session = $this->request->getPost("last_session");
        $user->status = $this->request->getPost("status");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "new"
            ));
        }

        $this->flash->success("user was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user",
            "action" => "index"
        ));

    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user = User::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "index"
            ));
        }

        $user->email = $this->request->getPost("email", "email");
        $user->name = $this->request->getPost("name");
        $user->lastname = $this->request->getPost("lastname");
        $user->facebook_id = $this->request->getPost("facebook_id");
        $user->facebook_token = $this->request->getPost("facebook_token");
        $user->google_id = $this->request->getPost("google_id");
        $user->created_at = $this->request->getPost("created_at");
        $user->updated_at = $this->request->getPost("updated_at");
        $user->language_id = $this->request->getPost("language_id");
        $user->last_session = $this->request->getPost("last_session");
        $user->status = $this->request->getPost("status");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "edit",
                "params" => array($user->id)
            ));
        }

        $this->flash->success("user was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user = User::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "index"
            ));
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user",
                "action" => "search"
            ));
        }

        $this->flash->success("user was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user",
            "action" => "index"
        ));
    }

}
