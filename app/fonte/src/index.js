import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import App from './App';
import * as serviceWorker from './serviceWorker';
import { UserProvider } from './context/AuthProvider';

const startApp = () => {
    try {
        const container = document.getElementById('root');
        const root = createRoot(container); // createRoot(container!) if you use TypeScript
        root.render(<App />);
    } catch (e) {
        alert(e)
    }
}
if (window.cordova) {
    document.addEventListener('deviceready', startApp, false);
    document.addEventListener('deviceready', navigator.splashscreen.hide());
} else {
    startApp();
}


// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.register();
