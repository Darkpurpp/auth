<?php

namespace app\controllers;

use yii\web\Controller;

use app\models\User;

use Yii;

class AuthController extends Controller {

    public function beforeAction($action) {
        /*if (in_array($action->id, ['index'])) {
            $this->enableCsrfValidation = false;
        }*/
        return parent::beforeAction($action);
    }

    public function actionIndex() {

        if( Yii::$app->user->isGuest ) {
            
            $this->layout = 'auth';

            return $this->render('index');
        }
        else
        {
            return $this->goHome();
        }
    }

    public function actionAuth() {

        if( Yii::$app->request->isAjax ) {

            $data = Yii::$app->request->post();

            $error = '';

            if( empty($data['password']) )
            {
                $error .= 'password';
            }

            $password = $data['password'];

            if( empty( $data['email'] ) || strpos( $data['email'], '@' ) === false )
            {
                $error .= 'email';
            }

            $email = $data['email'];

            $time = 0;

            if( strpos( $data['check'], 'active_hover' ) )
            {
                $time = 3600 * 24 * 30;
            }

            if( $data['version'] == '_apm' ) {

                $apm = $data['apm'];

                if( empty( $apm ) || strlen( $apm ) < 9 || $apm[0] == '0' )
                {
                    $error .= 'apm';
                }

                if( !empty( $error ) )
                {
                    return $error;
                }

                $user = User::findOne([
                    'password' => $password,
                    'apm' => $apm,
                    'email' => $email
                ]);

                if( !isset($user->id) )
                {
                    return 'apmpasswordemail';
                }

                Yii::$app->user->login($user, $time);

                return 1;

            }

            if( !empty( $error ) )
            {
                return $error;
            }

            $user = User::findOne([
                'password' => $password,
                'email' => $email
            ]);

            if( !isset($user->id) )
            {
                return 'passwordemail';
            }

            Yii::$app->user->login($user, $time);

            return 1;

        }

    }

    public function actionList() {

        if( !Yii::$app->user->isGuest )
        {
            $this->layout = 'auth';

            $users = User::find()->indexBy('id')->all();

            $table = '<table class="table table-bordered">'.
                '<thead>'.
                    '<tr>'.
                        '<th>Имя</th>'.
                        '<th>Логин</th>'.
                        '<th>Пароль</th>'.
                        '<th><span style="border-right: solid; padding-right: 30px">Действие</span>&nbsp&nbsp&nbsp&nbsp<button class="btn btn-sm btn-outline-danger logout">Out</button></th>'.
                    '</tr>'.
                '<thead>'.
                '<tbody>';

            foreach ($users as $user) {
                $table .=  '<tr id="' . $user->id . '">'.
                                '<td>' . $user->username .'</td>'.
                                '<td>' . $user->email. '</td>' .
                                '<td>' . $user->password . '</td>' .
                                '<td><button class="btn btn-danger">Удалить</button></td>' .
                            '<tr>';
            }

            $table .=  '<tr>'.
                            '<td><input class="form-control new"></td>'.
                            '<td><input class="form-control new"></td>' .
                            '<td><input class="form-control new"></td>' .
                            '<td><button class="btn btn-primary">Добавить</button></td>' .
                        '<tr>';

            $table .= '</tbody>'.
                    '</table>';


            return $this->render( 'list', [ 'table' => $table ] );
        }
        else
        {
            $this->goHome();
        }
    }

    public function actionApm() {
        $this->layout = 'auth';
        return $this->render('apm');
    }

    public function actionAdd() {

        if( Yii::$app->request->isAjax )
        {
            $model = new User();
            $data = Yii::$app->request->post();

            if( in_array( '' , [$data['email'],$data['password']] ) )
            {
                return 0;
            }

            $model->email = $data['email'];

            $model->username = $data['name'];

            $model->password = $data['password'];

            $model->save(false);

            $model->refresh();

            $html = '<tr id="' . $model->id . '">' .
                        '<td>'. $data['name'].'</td>'.
                        '<td>'.$data['email'].'</td>'.  
                        '<td>'.$data['password'].'</td>'. 
                        '<td><button class="btn btn-danger">Удалить</button></td>'.
                    '</tr';

                    return $html;
        }

    }

    public function actionDelete() {

        if( Yii::$app->request->isAjax )
        {
            $data =  Yii::$app->request->post();

            $user = User::findOne($data['id']);

            $user->delete();

            return 1;
        }

    }


    public function actionLogout() {

        Yii::$app->user->logout();

        return $this->goHome();
    }
}
