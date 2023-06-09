import React from 'react';
import './App.scss';
import AppRouter from './Router';
import { ThemeProvider } from '@mui/material';
import { global } from './theme/global';
import { UserProvider } from './context/AuthProvider';


function App() {
  return (
    <div className="App" id="top">
      <ThemeProvider theme={global}>
        <UserProvider>
          <AppRouter />
        </UserProvider>
      </ThemeProvider>
    </div>
  );
}

export default App;
