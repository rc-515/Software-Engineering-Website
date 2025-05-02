import React, { useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert, TouchableOpacity } from 'react-native';
import { loginUser } from '../services/api';

export default function LoginScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const login = async () => {
    if (!email || !password) {
      return Alert.alert("Missing Info", "Please enter email and password");
    }

    try {
      const response = await loginUser({ email, password });
      navigation.navigate('Dashboard', { user: response.data });
    } catch (err) {
      Alert.alert("Login Failed", err.response?.data?.error || "Invalid credentials");
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Welcome Back!</Text>
      <Text style={styles.subtitle}>
        Log in to continue your journey and find your next match.
      </Text>

      <View style={styles.formBox}>
        <TextInput
          style={styles.input}
          placeholder="Enter your email"
          placeholderTextColor="#94a3b8"
          value={email}
          onChangeText={setEmail}
          autoCapitalize="none"
        />
        <TextInput
          style={styles.input}
          placeholder="Enter your password"
          placeholderTextColor="#94a3b8"
          secureTextEntry
          value={password}
          onChangeText={setPassword}
        />

        <View style={styles.buttonWrapper}>
          <Button title="Log In" color="#ef4444" onPress={login} />
        </View>
      </View>

      <Text style={styles.footerText}>
        Don't have an account?{' '}
        <Text style={styles.link} onPress={() => navigation.navigate('Register')}>
          Sign up.
        </Text>
      </Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    backgroundColor: '#0f172a',
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 30,
  },
  title: {
    color: '#ffffff',
    fontSize: 28,
    fontWeight: 'bold',
    marginBottom: 10,
  },
  subtitle: {
    color: '#cbd5e1',
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 25,
  },
  formBox: {
    backgroundColor: '#1e293b',
    borderRadius: 10,
    padding: 20,
    width: '100%',
    maxWidth: 400,
  },
  input: {
    backgroundColor: '#334155',
    color: '#ffffff',
    padding: 12,
    marginBottom: 15,
    borderRadius: 6,
  },
  buttonWrapper: {
    marginTop: 5,
  },
  footerText: {
    color: '#cbd5e1',
    marginTop: 25,
    textAlign: 'center',
  },
  link: {
    color: '#ef4444',
    fontWeight: 'bold',
  },
});
