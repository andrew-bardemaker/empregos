import React, { Component } from 'react'

const UserContext = React.createContext()

class UserProvider extends Component {
    // Context state
    state = {
        userID: null,
        user: {},
        tipoConta: null
    };

    // Method to update state
    signIn = (user, userID, tipoConta) => {
        this.setState({
            user: user,
            userID: userID,
            tipoConta: tipoConta
        });
    }

    signOut = () => {
        this.setState({
            userID: null,
            user: {},
            tipoConta: null
        });
    }

    render() {
        const { children } = this.props;
        const { user } = this.state;
        const { userID } = this.state;
        const { signIn } = this;
        const { signOut } = this;

        return (
            <UserContext.Provider
                value={{
                    userID,
                    user,
                    signIn,
                    signOut
                }}
            >
                {children}
            </UserContext.Provider>
        )
    }
}

export default UserContext

export { UserProvider }