import * as React from 'react';
import {useRef, useState, useEffect} from "react";
import axiosClient from "../axios_client.js";
import { useStateContext } from "../contexts/ContextProvider.jsx";
import { useNavigate } from 'react-router-dom';

import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import CssBaseline from '@mui/material/CssBaseline';
import TextField from '@mui/material/TextField';
import FormControlLabel from '@mui/material/FormControlLabel';
import Checkbox from '@mui/material/Checkbox';
import Link from '@mui/material/Link';
import Grid from '@mui/material/Grid';
import Box from '@mui/material/Box';
import Alert from '@mui/material/Alert';
import AlertTitle from '@mui/material/AlertTitle';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import Typography from '@mui/material/Typography';
import Container from '@mui/material/Container';
import CloseIcon from '@mui/icons-material/Close';
import IconButton from '@mui/material/IconButton';
import { createTheme, ThemeProvider } from '@mui/material/styles';
import {Copyright} from "@mui/icons-material";

const defaultTheme = createTheme({
  palette: {
    primary: {
      main: '#8B0000',
    },
    secondary: {
      main: '#F5A623',
    },
  },
});

export default function Signin() {
  const emailRef = useRef();
  const passwordRef = useRef();
  const { setCurrentUser, setToken } = useStateContext();
  const navigate = useNavigate();

  /**
   * To set errors and alerts
   */
  const [errors, setErrors] = useState(null);
  const [alertMessage, setAlertMessage] = useState(null);
  const [alertType, setAlertType] = useState('error');
  const [loading, setLoading] = useState(false);

  /**
   * Auto-close alert after 5 seconds
   */
  useEffect(() => {
    if (alertMessage) {
      const timer = setTimeout(() => {
        setAlertMessage(null);
      }, 5000);
      return () => clearTimeout(timer);
    }
  }, [alertMessage]);

  /**
   * Validate email format using regex
   */
  const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  /**
   * Validate password - minimum 3 characters
   */
  const isValidPassword = (password) => {
    return password && password.trim().length >= 3;
  };

  /**
   * Client-side validation before form submission
   */
  const validateForm = () => {
    const email = emailRef.current.value.trim();
    const password = passwordRef.current.value.trim();
    const validationErrors = {};

    // Validate email
    if (!email) {
      validationErrors.email = ['Email is required'];
    } else if (!isValidEmail(email)) {
      validationErrors.email = ['Please enter a valid email address'];
    }

    // Validate password
    if (!password) {
      validationErrors.password = ['Password is required'];
    } else if (!isValidPassword(password)) {
      validationErrors.password = ['Password must be at least 3 characters long'];
    }

    return { isValid: Object.keys(validationErrors).length === 0, errors: validationErrors };
  };

  function onSubmit(e) {
    e.preventDefault();
    setLoading(true);

    // Validate form before sending to backend
    const validation = validateForm();
    
    if (!validation.isValid) {
      setErrors(validation.errors);
      // Show alert with validation errors
      const errorMessages = Object.values(validation.errors).flat();
      setAlertMessage(errorMessages.join(' • '));
      setAlertType('error');
      setLoading(false);
      return;
    }

    // Clear previous errors
    setErrors(null);
    setAlertMessage(null);

    const payload = {
      email: emailRef.current.value.trim(),
      password: passwordRef.current.value.trim()
    }

    /**
     * Send the payload to the backend API
     */
    axiosClient.post("/login", payload)
      .then((result) => {
        /**
         * If the user is successfully created, we will set the current user and token
         * automatically redirect to the home page ...
         */
        setCurrentUser(result.data.data.user);
        setToken(result.data.data.token);
        setAlertMessage('Login successful! Redirecting...');
        setAlertType('success');
        setLoading(false);
        navigate('/dashboard');
      })
      .catch((err) => {
        const response = err.response;
        setLoading(false);

        if (response && response.status === 422) {
          setErrors(response.data.errors);
          const errorMessages = Object.values(response.data.errors).flat();
          setAlertMessage(errorMessages.join(' • '));
          setAlertType('error');
        } else if (response && response.status === 401) {
          setErrors({ general: ['Invalid credentials'] });
          setAlertMessage('❌ Invalid email or password. Please try again.');
          setAlertType('error');
        } else {
          setErrors({ general: ['An error occurred. Please try again.'] });
          setAlertMessage('⚠️ An error occurred. Please try again.');
          setAlertType('error');
        }
      });
  }

  const closeAlert = () => {
    setAlertMessage(null);
  };

  return (
    <ThemeProvider theme={defaultTheme}>
      <CssBaseline />
      <Container component="main" maxWidth="xs">
        <Box
          sx={{
            marginTop: { xs: 4, sm: 8 },
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            padding: { xs: '16px', sm: '0px' },
          }}
        >
          {/* Alert Message */}
          {alertMessage && (
            <Alert 
              severity={alertType}
              sx={{
                width: '100%',
                marginBottom: 2,
                animation: 'slideIn 0.3s ease-in-out',
                '@keyframes slideIn': {
                  from: {
                    transform: 'translateY(-20px)',
                    opacity: 0,
                  },
                  to: {
                    transform: 'translateY(0)',
                    opacity: 1,
                  },
                },
              }}
              action={
                <IconButton
                  size="small"
                  color="inherit"
                  onClick={closeAlert}
                >
                  <CloseIcon fontSize="small" />
                </IconButton>
              }
            >
              <AlertTitle>{alertType === 'error' ? 'Validation Error' : 'Success'}</AlertTitle>
              {alertMessage}
            </Alert>
          )}

          <Avatar 
            sx={{ 
              m: 1, 
              bgcolor: 'primary.main',
              width: { xs: 40, sm: 56 },
              height: { xs: 40, sm: 56 },
            }}
          >
            <LockOutlinedIcon />
          </Avatar>
          
          <Typography 
            component="h1" 
            variant="h5"
            sx={{
              fontSize: { xs: '20px', sm: '24px' },
              marginBottom: 2,
            }}
          >
            Sign in
          </Typography>

          <Box 
            component="form" 
            onSubmit={onSubmit} 
            noValidate 
            sx={{ 
              mt: 1,
              width: '100%',
            }}
          >
            <TextField
              margin="normal"
              required
              fullWidth
              id="email"
              ref={emailRef}
              name="email"
              label="Email"
              type="email"
              autoComplete="email"
              autoFocus
              error={errors && errors.email ? true : false}
              helperText={errors && errors.email ? errors.email[0] : ''}
              sx={{
                '& .MuiOutlinedInput-root': {
                  fontSize: { xs: '14px', sm: '16px' },
                }
              }}
            />

            <TextField
              margin="normal"
              required
              fullWidth
              name="password"
              label="Password"
              type="password"
              id="password"
              ref={passwordRef}
              autoComplete="current-password"
              error={errors && errors.password ? true : false}
              helperText={errors && errors.password ? errors.password[0] : ''}
              sx={{
                '& .MuiOutlinedInput-root': {
                  fontSize: { xs: '14px', sm: '16px' },
                }
              }}
            />

            <FormControlLabel
              control={<Checkbox color="primary" />}
              label="Remember me"
              sx={{
                fontSize: { xs: '12px', sm: '14px' },
              }}
            />

            <Button
              type="submit"
              fullWidth
              variant="contained"
              disabled={loading}
              sx={{ 
                mt: 3, 
                mb: 2,
                padding: { xs: '10px', sm: '12px' },
                fontSize: { xs: '14px', sm: '16px' },
              }}
            >
              {loading ? 'Signing in...' : 'Sign In'}
            </Button>

            <Grid container spacing={1}>
              <Grid item xs={12} sm="auto">
                <Link 
                  href="#" 
                  variant="body2"
                  sx={{
                    fontSize: { xs: '12px', sm: '14px' },
                  }}
                >
                  Forgot password?
                </Link>
              </Grid>
              <Grid item xs={12} sm="auto">
                <Link 
                  href="/signup" 
                  variant="body2"
                  sx={{
                    fontSize: { xs: '12px', sm: '14px' },
                  }}
                >
                  Create an account
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>

        <Copyright sx={{ 
          mt: { xs: 4, sm: 8 }, 
          mb: 2,
          fontSize: { xs: '12px', sm: '14px' },
        }} />
      </Container>
    </ThemeProvider>
  );
}
