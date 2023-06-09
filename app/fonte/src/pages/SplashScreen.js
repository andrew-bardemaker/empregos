import React, { Component } from 'react';
import { Redirect } from 'react-router-dom'
import ReactTimeout from 'react-timeout'
import { splashLaranja } from '../assets/images/splash';

class SplashScreen extends Component {
    state = {
        redirect: false,
    }
    componentDidMount() {
        this.props.setTimeout(this.router, 5000)
    }

    componentWillUnmount() {
        this.props.clearInterval(this.router);
    }

    router = () => {
        let driver = localStorage.getItem('driver') || '';
        let token_invalido = localStorage.getItem('token_invalido') || '';

        if (driver === '' && token_invalido !== '') {
            localStorage.clear();
            this.setState({ redirect: 'home' });
        }

        if (driver !== '' && token_invalido !== '') {
            localStorage.clear();
            this.setState({ redirect: 'home' });
        }

        if (driver !== '' && token_invalido === '') {
            this.setState({ redirect: 'homeentregador' })
        }

        if (driver === '' && token_invalido === '') {
            this.setState({ redirect: 'home' });
        }
    }

    render() {
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return <div className='viewSplash'>
            <img className='img-splash' src={splashLaranja} alt="splash" />
        </div>
    }
}

export default ReactTimeout(SplashScreen);