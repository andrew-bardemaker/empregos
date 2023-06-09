import React, { useContext, useState } from "react";
import * as yup from 'yup';
import { useFormik } from "formik";
import api from "../services/api";
import { useEffect } from "react";
import { Box, Button, Card, CardMedia, Checkbox, CircularProgress, TextField, Typography, useTheme } from "@mui/material";
import { Link, useNavigate } from "react-router-dom";
import AuthContext from "../contexts/AuthProvider";
import ReCAPTCHA from "react-google-recaptcha";

const validationSchema = yup.object({
  email: yup
    .string('Insira seu email ou user')
    .required('Email é um campo necessário'),
  password: yup
    .string('Insira sua senha')
    .min(5, 'Sua senha deve conter ao menos 5 caracteres')
    .required('Senha é um campo necessário'),
  recaptcha: yup
    .string()
    .required()
});

export default function Login() {

  const theme = useTheme();

  const [errorMessage, setErrorMessage] = useState("");

  const [check, setCheck] = useState(false);
  const [userEmail, setEmail] = useState('');
  const [userPassword, setPassword] = useState('');
  const [privacy, setPrivacy] = useState(false);
  const [privMessage, setPrivMessage] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const navigate = useNavigate();

  async function storageUser(data) {
    await localStorage.setItem('localUser', JSON.stringify(data))
  };

  async function loadUser() {
    const response = JSON.parse(await localStorage.getItem('localUser'))
    if (response != null || '') {
      setEmail(response.email);
      setPassword(response.password);
    }
  };

  async function loadCheck() {
    var checkData = localStorage.getItem('appCheck')
    if (checkData != null || '') {
      setCheck(JSON.parse(true));
      loadUser();
    }
  };

  async function storageCheck() {
    await localStorage.setItem('appCheck', JSON.stringify(check))
  };

  const { signIn } = useContext(AuthContext);

  useEffect(() => {
    loadCheck();
  }, []);

  const formik = useFormik({
    initialValues: {
      email: userEmail,
      password: userPassword,
    },
    validationSchema: validationSchema,
    onSubmit: (e) => { executeLogin(e.email, e.password) }
  });

  async function executeLogin(email, password) {
    if (privacy) {
      setIsLoading(true);
      setPrivMessage('');
      await api.post('login.php', { user: email, senha: password })
        .then((response) => {
          if (response.data.error) {
            setErrorMessage(response.data.msg);
          } else {
            if (check) {
              storageUser({ email: email, password: password });
              storageCheck();
            } else {
              localStorage.removeItem('appCheck');
            };

            const user = response.data.user;
            setErrorMessage('');
            signIn(user);
            navigate('/');
          };
          setIsLoading(false);
        })
        .catch((error) => {
          setErrorMessage('Erro na conexão, tente novamente mais tarde!');
          setIsLoading(false);
          console.log(error);
        });
    } else {
      setPrivMessage('Para acessar a plataforma, você deve concordar com a Política de Privacidade');
    }
  };

  function onChange(value) {
    if (value.length === 0) {
      console.log('NO BOTS HEEERE!');
    }
    else {
      formik.setFieldValue('recaptcha', `${value}`)
    }
  };

  return (
    <Card sx={{
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      flexDirection: 'column',
      p: '2rem',
      m: '1rem'
    }}>
      <CardMedia
        component="img"
        image='https://dedstudio.com.br/bejobs/admin/images/logo.png'
        alt="App logo"
        sx={{
          width: '10em',
          height: '10em',
        }}
      />
      <form onSubmit={formik.handleSubmit}>
        <TextField
          fullWidth
          id="email"
          name="email"
          label="Email ou Usuário"
          value={formik.values.email || ''}
          onChange={formik.handleChange}
          error={formik.touched.email && Boolean(formik.errors.email)}
          helperText={formik.touched.email && formik.errors.email}
          InputLabelProps={{ shrink: true }}
          sx={{
            mt: '2rem'
          }}
        />
        <TextField
          fullWidth
          id="password"
          name="password"
          label="Senha"
          type="password"
          value={formik.values.password || ''}
          onChange={formik.handleChange}
          error={formik.touched.password && Boolean(formik.errors.password)}
          helperText={formik.touched.password && formik.errors.password}
          InputLabelProps={{ shrink: true }}
          sx={{
            m: '1rem 0'
          }}
        />

        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
          async defer>
        </script>

        <ReCAPTCHA
          id='recaptcha'
          name='recaptcha'
          sitekey="6LeetE4kAAAAAFXEGNjUbcfLox_FhCFmX39CNi4O"
          onChange={onChange}
        />

        <Box sx={{
          display: 'flex',
          justifyContent: 'left',
          alignItems: 'center',
          flexDirection: 'row',
          mt: '1rem'
        }}>
          <Checkbox checked={privacy} onChange={() => { setPrivacy(!privacy) }} />
          <Typography>
            Concordo com as <Link to='/TermosEPrivacidade' style={{ textDecoration: 'none' }}><strong>Políticas de Privacidade</strong></Link>
          </Typography>
        </Box>
        {
          isLoading ?
            <Box sx={{ width: '100%', height: '100%', display: 'flex', justifyContent: 'center', alignItems: 'center', mt: '1rem' }}>
              <CircularProgress size={'2em'} color="primary" sx={{ m: 'auto' }} />
            </Box>
            :
            <Button color="primary" variant="contained" fullWidth type="submit" sx={{
              mt: '1rem',
            }}>
              Login
            </Button>
        }
        {
          privMessage &&
          <Typography textAlign='center' color='red' mt='1rem'>
            Para acessar a plataforma, você deve concordar com a Política de Privacidade
          </Typography>
        }
        {
          errorMessage &&
          <Typography textAlign='center' color='red' mt='1rem'>
            {errorMessage}
          </Typography>
        }
        <Box sx={{
          display: 'flex',
          justifyContent: 'left',
          alignItems: 'center',
          flexDirection: 'row',
          mt: '1rem'
        }}>
          <Link to='/Cadastro' style={{ textDecoration: 'none', margin: 'auto' }}>
            <Typography fontWeight={700} color="primary">
              Me cadastrar agora!
            </Typography>
          </Link>
        </Box>
      </form>
    </Card>
  );
}