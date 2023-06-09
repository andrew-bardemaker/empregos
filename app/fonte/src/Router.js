import React, { Component } from 'react';
import { HashRouter as Router, Route } from 'react-router-dom'
import Home from './pages/Home'
import SplashScreen from './pages/SplashScreen';
import Login from './pages/Login';
import ForgotPass from './pages/ForgotPass';
import Register from './pages/Register';
import ComingSoon from './pages/ComingSoon';
import Notifications from './pages/Notifications';
import Notification from './pages/Notification';
import Profile from './pages/Profile';
import Help from './pages/Help';
import Cart from './pages/Cart';
import Faq from './pages/Faq';
import Adress from './pages/Adress';
import NewAdress from './pages/NewAdress';
import NewAdressCheckout from './pages/NewAdressCheckout';
import Orders from './pages/Orders';
import OrdersDetails from './pages/OrdersDetails';
import Checkout from './pages/Checkout';
import Calls from './pages/Calls';
import CallDetails from './pages/CallDetails';
import NewCall from './pages/NewCall';
import Terms from './pages/Terms';

// DRIVER
import LoginDriver from './pages/LoginDriver';
import HomeDriver from './pages/HomeDriver';
import OrderDriver from './pages/OrderDriver';
import StorageDriver from './pages/StorageDriver';
import ScrollToTop from './components/ScrollToTop';
import LandingPage from './pages/LandingPage';
import Partner from './pages/Partner';


class AppRouter extends Component {

  render() {
    return (

      <div className="Router">
        <Router>
          <ScrollToTop>
            <Route exact path='/' component={SplashScreen} />
            <Route path='/home' component={Home} />
            <Route path='/landingpage' component={LandingPage} />
            <Route path='/login' component={Login} />
            <Route path='/esqueciasenha' component={ForgotPass} />
            <Route path='/cadastro' component={Register} />
            <Route path='/ajuda' component={Help} />
            <Route path='/sacola' component={Cart} />
            <Route path='/perfil' component={Profile} />
            <Route path='/notificacoes' component={Notifications} />
            <Route path='/notificacao/:id' component={Notification} />
            <Route path='/comingsoon' component={ComingSoon} />
            <Route path='/faq' component={Faq} />
            <Route path='/enderecos' component={Adress} />
            <Route path='/cadastroendereco' component={NewAdress} />
            <Route path='/enderecocheckout' component={NewAdressCheckout} />
            <Route path='/pedidos' component={Orders} />
            <Route path='/pedido/:id' component={OrdersDetails} />
            <Route path='/checkout' component={Checkout} />
            <Route path='/chamados' component={Calls} />
            <Route path='/novochamado' component={NewCall} />
            <Route path='/chamado/:id' component={CallDetails} />
            <Route path='/termosdeuso' component={Terms} />
            <Route path='/parceiro' component={Partner} />

            {/* DRIVER */}

            <Route path='/loginentregador' component={LoginDriver} />
            <Route path='/homeentregador' component={HomeDriver} />
            <Route path='/pedidoentregador/:id' component={OrderDriver} />
            <Route path='/estoque' component={StorageDriver} />

          </ScrollToTop>
        </Router>

      </div>
    );
  }
}

export default AppRouter;