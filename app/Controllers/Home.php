<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    protected $user; // Variable pour stocker l'instance du modèle UserModel

    public function __construct()
    {
        helper(['url']); // Charger le helper URL
        $this->user = new UserModel(); // Initialiser le modèle UserModel
    }

    public function index()
    {
        // Vérifier que les vues existent
        if (!file_exists(APPPATH . 'Views/inc/header.php') ||
            !file_exists(APPPATH . 'Views/inc/home.php') ||
            !file_exists(APPPATH . 'Views/inc/footer.php')) {
            throw new \Exception('One or more view files are missing');
        }

        // Charger les parties de la vue
        $header = view('inc/header');
        $data['users'] = $this->user->orderBy('id', 'DESC')->paginate(3, 'group1'); // Récupérer les utilisateurs paginés
        $data['pager'] = $this->user->pager;
        $home = view('inc/home', $data);
        $footer = view('inc/footer');

        // Combiner les vues et les retourner
        return $header . $home . $footer;
    }

    
    public function saveUser()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');

        if ($this->user->save(['username' => $username, 'email' => $email])) {
            session()->setFlashdata("success", "Data inserted successfully");
        } else {
            session()->setFlashdata("error", "Data insertion failed");
        }

        return redirect()->to(base_url());
    }

}
