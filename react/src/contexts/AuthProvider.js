import React from 'react'
import { useEffect } from 'react';
import api from '../services/api';

const AuthContext = React.createContext({})

function AuthProvider({ children }) {

    //Sender informations:
    const [userData, setUserData] = React.useState(null);
    const [userType, setUserType] = React.useState(null);

    const localUserToken = localStorage.getItem("bejobs-user-token");

    function signIn(data) {
        setUserData(data);
        setUserType(data.tipo_usuario);
        localStorage.setItem('bejobs-user-token', JSON.stringify(data.token))
    };

    function signOut() {
        setUserData(null);
        setUserType(null);
        localStorage.removeItem("bejobs-user-token");
    };

    function verifyToken(token) {

        let localToken = JSON.parse(token);

        api.post('verifica-token-usuario.php', { token: localToken })
            .then(res => {
                if (res.data.success) {
                    signIn(res.data.user);
                };
            })
            .catch(error => {
                console.log(error);
            });
    };

    useEffect(() => {
        localUserToken && verifyToken(localUserToken);
    }, []);

    return (
        <AuthContext.Provider
            value={{
                userData,
                userType,
                signIn,
                signOut
            }}
        >
            {children}
        </AuthContext.Provider>
    )
}

export default AuthContext;

export { AuthProvider };