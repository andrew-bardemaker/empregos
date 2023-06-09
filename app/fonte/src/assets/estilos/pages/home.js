export const useStyles = theme => ({
    appBarSpacer: theme.mixins.toolbar,
    topSpace: {
        height: theme.spacing(11),
    },
    bottomSpace: {
        height: theme.spacing(2),
    },
    rootLightGray: {
        width: '100%',
        height: '100%',
        minHeight: '100vh',
        backgroundColor: '#F9F9F9',
    },
    container: {
        paddingTop: theme.spacing(2),
        paddingBottom: theme.spacing(2),
    },
    containerRounded: {
        paddingTop: theme.spacing(4),
        paddingBottom: theme.spacing(4),
        borderRadius: '4px',
        backgroundColor: '#fff',
    },
    paper: {
        padding: theme.spacing(2),
        display: 'flex',
        overflow: 'auto',
        flexDirection: 'column',
    },
    gridProducts: {
        marginTop: 10,
    },
    ageModalAvatar: {
        width: 100,
        height: 100,
        textAlign: 'center',
    },
    ageModal: {
        textAlign: 'center',
        color: '#FF8A00',
        backgroundColor: '#444444',
    },
    ageModalTitle: {
        textTransform: 'uppercase',
        color: '#444444',
        fontFamily: 'Bebas Neue, cursive !important',
    },
    ageModalText: {
        color: '#444444',
    },
    ageModalButton: {
        width: '50%',
        textAlign: 'center',
        textTransform: 'uppercase',
        color: '#444444',
        fontFamily: 'Bebas Neue, cursive !important',
        backgroundColor: '#FF8A00',
    },
    ageModalButtonLink: {
        width: '100%',
        textAlign: 'center',
        textTransform: 'uppercase',
        color: '#444444',
        fontFamily: 'Bebas Neue, cursive !important',
        backgroundColor: '#FF8A00',
    },
    ageModalLink: {
        padding: 'auto',
        width: '50%',
    },
    bannerCaroussel: {
        height: 'auto',
        width: '100%',
        position: 'relative',
    },
    title: {
        textTransform: 'uppercase',
        color: '#444444',
        fontSize: 40,
        fontFamily: 'Bebas Neue, cursive !important',
    },
    small_title: {
        textTransform: 'uppercase',
        color: '#FF8A00',
        fontSize: 20,
        fontFamily: 'Bebas Neue, cursive !important',
    },
    buttonProduct1: {
        borderRadius: '30px',
        width: '100%',
        // color: '#fff !important',
    },
    backdrop: {
        zIndex: theme.zIndex.drawer + 1,
        color: '#fff',
    },
    buttonProduct2: {
        width: '100%',
        color: '#fff !important',
    },
    button: {
        width: '96%',
        marginLeft: '2%',
        fontWeight: 500,
    },
    formControl: {
        marginRight: theme.spacing(2),
        width: '100%',
    },
    selectEmpty: {
        marginTop: theme.spacing(2),
    },

});