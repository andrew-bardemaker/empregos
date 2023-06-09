import React from "react"
import { AppBar, IconButton, styled, Toolbar } from "@material-ui/core";
import { MenuBook } from "@material-ui/icons";

const CustomAppBar = () => {

    return (
        <AppBar position="fixed" color="primary" sx={{  }}>
            <Toolbar>
                <IconButton color="inherit" aria-label="open drawer">
                    <MenuBook />
                </IconButton>
                <IconButton color="inherit">
                    xx
                </IconButton>
                <IconButton color="inherit">
                    xx
                </IconButton>
            </Toolbar>
        </AppBar>
    )
}

export default CustomAppBar;