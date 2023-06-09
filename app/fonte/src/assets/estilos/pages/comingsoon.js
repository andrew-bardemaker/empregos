export const useStyles = theme => ({
  comingSoon: {
    backgroundColor: '#FF8A00',
  },
  paper: {
    marginTop: theme.spacing(4),
    paddingTop: theme.spacing(2),
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    backgroundColor: '#FF8A00',
    borderRadius: 8,
  },
  avatar: {
    width: 180,
    height: 180,
  },
  form: {
    width: '100%', // Fix IE 11 issue.
    marginTop: theme.spacing(3),
  },
  submit: {
    margin: theme.spacing(3, 0, 2),
    backgroundColor: '#444444',
    color: '#fff'
  },
  title: {
    textTransform: 'uppercase',
    color: '#444444',
    fontSize: 40,
    fontFamily: 'Bebas Neue, cursive !important',
  },
  titleSmall: {
    // textTransform: 'uppercase',
    fontSize: 18,
    color: '#444444',
  },
  titleMedium: {
    textTransform: 'uppercase',
    fontSize: 16,
    color: '#444444',
  },
  rodape: {
    fontSize: 13,
    color: '#444444',
  }
});
