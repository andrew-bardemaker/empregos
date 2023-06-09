import { createTheme } from "@material-ui/core";

export const global = createTheme({
    palette: {
        primary: {
            main: '#ff8a00'
        },
        text: {
            primary: '#444444'
        }
    },
    shape: {
        borderRadius: 60
    },
    typography: {
        fontFamily: "Poppins",
        htmlFontSize: 16,
        fontSize: 16
    }
})