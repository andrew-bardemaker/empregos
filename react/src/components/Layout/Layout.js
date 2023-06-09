import { Box, useTheme } from "@mui/material";
import { Fragment } from "react";
import Footer from "./Footer";
import Header from "./Header";

export default function Layout({ children }) {

    const theme = useTheme();

    const styles = {
        layout: {
            width: '100vw',
            mt: '5em',
            minHeight: '88vh',
            bgcolor: theme.palette.primary.main,
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'start',
        }
    };

    return (
        <Fragment>
            <Header />
            <Box sx={styles.layout}>
                {children}
            </Box>
            <Footer />
        </Fragment>
    );
};