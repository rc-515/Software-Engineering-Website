import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  Button,
  Alert,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
} from 'react-native';
import { registerUser } from '../services/api';
import { useNavigation } from '@react-navigation/native';

export default function RegisterScreen() {
  const navigation = useNavigation();

  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [weight, setWeight] = useState('');
  const [height, setHeight] = useState('');
  const [bench, setBench] = useState('');
  const [experience, setExperience] = useState('');

  const handleRegister = async () => {
    if (!fullName.trim() || !email.trim() || !password || !weight || !height || !bench || !experience) {
      Alert.alert('Missing Fields', 'Please fill out all fields.');
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.trim())) {
      Alert.alert('Invalid Email', 'Please enter a valid email address.');
      return;
    }

    if (password.length < 10) {
      Alert.alert('Weak Password', 'Password must be at least 10 characters long.');
      return;
    }

    if (!/^\d+$/.test(weight)) {
      Alert.alert('Invalid Weight', 'Weight must be a whole number.');
      return;
    }

    if (!/^\d+$/.test(height)) {
      Alert.alert('Invalid Height', 'Height must be a whole number.');
      return;
    }

    if (!/^\d+$/.test(bench)) {
      Alert.alert('Invalid Bench Press', 'Bench press max must be a whole number.');
      return;
    }

    try {
      const response = await registerUser({
        full_name: fullName.trim(),
        email: email.trim(),
        password,
        weight: parseInt(weight),
        height: parseInt(height),
        bench_press: parseInt(bench),
        experience,
      });

      Alert.alert('Success', 'Registration complete!');
      navigation.navigate('Login');
    } catch (error) {
      console.error('❌ Registration failed:', error);
      Alert.alert('Error', 'Registration failed. Please try again.');
    }
  };

  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.header}>Create an Account</Text>

      <TextInput
        placeholder="Full Name"
        placeholderTextColor="#999"
        value={fullName}
        onChangeText={setFullName}
        style={styles.input}
      />
      <TextInput
        placeholder="Email"
        placeholderTextColor="#999"
        value={email}
        onChangeText={setEmail}
        keyboardType="email-address"
        style={styles.input}
      />
      <TextInput
        placeholder="Password"
        placeholderTextColor="#999"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
        style={styles.input}
      />
      <TextInput
        placeholder="Weight (lbs)"
        placeholderTextColor="#999"
        value={weight}
        onChangeText={setWeight}
        keyboardType="numeric"
        style={styles.input}
      />
      <TextInput
        placeholder="Height (inches)"
        placeholderTextColor="#999"
        value={height}
        onChangeText={setHeight}
        keyboardType="numeric"
        style={styles.input}
      />
      <TextInput
        placeholder="Bench Press (lbs)"
        placeholderTextColor="#999"
        value={bench}
        onChangeText={setBench}
        keyboardType="numeric"
        style={styles.input}
      />

      <Text style={styles.label}>Select Experience Level:</Text>
      <View style={styles.experienceGroup}>
        {['Beginner', 'Intermediate', 'Advanced'].map((level) => (
          <TouchableOpacity
            key={level}
            style={[
              styles.experienceButton,
              experience === level && styles.experienceSelected,
            ]}
            onPress={() => setExperience(level)}
          >
            <Text style={styles.experienceText}>{level}</Text>
          </TouchableOpacity>
        ))}
      </View>

      <TouchableOpacity style={styles.button} onPress={handleRegister}>
        <Text style={styles.buttonText}>Sign Up</Text>
      </TouchableOpacity>

      <Text style={styles.footerText}>
        Already have an account?{' '}
        <Text style={styles.link} onPress={() => navigation.navigate('Login')}>
          Log in.
        </Text>
      </Text>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    backgroundColor: '#1a1c23',
    padding: 20,
    flexGrow: 1,
    justifyContent: 'center',
  },
  header: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
    alignSelf: 'center',
    marginBottom: 30,
  },
  input: {
    backgroundColor: '#2d3748',
    color: '#fff',
    padding: 12,
    borderRadius: 8,
    marginBottom: 15,
  },
  label: {
    color: '#fff',
    fontWeight: '600',
    marginBottom: 10,
  },
  experienceGroup: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 25,
  },
  experienceButton: {
    flex: 1,
    padding: 10,
    backgroundColor: '#4a5568',
    borderRadius: 8,
    marginHorizontal: 5,
    alignItems: 'center',
  },
  experienceSelected: {
    backgroundColor: '#e53e3e',
  },
  experienceText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  button: {
    backgroundColor: '#e53e3e',
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
    marginBottom: 20,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  footerText: {
    color: '#ccc',
    textAlign: 'center',
  },
  link: {
    color: '#e53e3e',
    fontWeight: '600',
  },
});
