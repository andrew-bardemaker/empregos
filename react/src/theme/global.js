import { createTheme } from '@mui/material';

let global = createTheme({
    typography: {
        fontFamily: 'sans-serif',
        color: 'primary'
    }
});

global = createTheme(global, {
    typography: {
        h1: {
            fontFamily: 'Montserrat',
            fontWeight: 700,
            fontSize: '2em',
        },
        h2: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
            fontSize: '1.5em',
        },
        h3: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
            fontSize: '1.15em',
        },
        button: {
            fontFamily: 'Montserrat',
            fontWeight: 700,
            textTransform: 'none',
        }
    },
    palette: {
        primary: {
            main: '#142736',
        },
        secondary: {
            main: '#ffdc74',
        },
        background: {
            main: '#2c5576'
        }
    },
    shape: {
        borderRadius: 8
    },
    shadows: Array(25).fill('0px 5px 5px rgba(0, 0, 0, 0.4)')
})

export default global;