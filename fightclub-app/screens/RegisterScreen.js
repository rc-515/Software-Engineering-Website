import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet } from 'react-native';
import { registerUser } from '../services/api'; // Axios call
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
    // 🔒 Custom validations
    if (!fullName.trim() || !email.trim() || !password || !weight || !height || !bench || !experience) {
      Alert.alert("Missing Fields", "Please fill out all fields.");
      return;
    }
  
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.trim())) {
      Alert.alert("Invalid Email", "Please enter a valid email address.");
      return;
    }
  
    if (password.length < 10) {
      Alert.alert("Weak Password", "Password must be at least 10 characters long.");
      return;
    }
  
    if (!/^\d+$/.test(weight)) {
      Alert.alert("Invalid Weight", "Weight must be a whole number.");
      return;
    }
  
    if (!/^\d+$/.test(height)) {
      Alert.alert("Invalid Height", "Height must be a whole number.");
      return;
    }
  
    if (!/^\d+$/.test(bench)) {
      Alert.alert("Invalid Bench Press", "Bench press max must be a whole number.");
      return;
    }
  
    // ✅ Passed all validation — now try the API
    try {
      const response = await registerUser({
        full_name: fullName.trim(),
        email: email.trim(),
        password,
        weight: parseInt(weight),
        height: parseInt(height),
        bench_press: parseInt(bench),
        experience
      });
  
      Alert.alert("Success", "Registration complete!");
      navigation.navigate('Login');
    } catch (error) {
      console.error("❌ Registration failed:", error);
      Alert.alert("Error", "Registration failed. Please try again.");
    }
  };
  

  return (
    <View style={styles.container}>
      <Text>Register</Text>

      <TextInput placeholder="Full Name" value={fullName} onChangeText={setFullName} />
      <TextInput placeholder="Email" value={email} onChangeText={setEmail} keyboardType="email-address" />
      <TextInput placeholder="Password" value={password} onChangeText={setPassword} secureTextEntry />
      <TextInput placeholder="Weight (lbs)" value={weight} onChangeText={setWeight} keyboardType="numeric" />
      <TextInput placeholder="Height (inches)" value={height} onChangeText={setHeight} keyboardType="numeric" />
      <TextInput placeholder="Bench Press (lbs)" value={bench} onChangeText={setBench} keyboardType="numeric" />
      <Text style={{ marginTop: 10 }}>Experience Level:</Text>
        <View style={styles.buttonGroup}>
          {["Beginner", "Intermediate", "Advanced"].map((level) => (
            <Button
              key={level}
              title={level}
              color={experience === level ? "blue" : "gray"}
              onPress={() => setExperience(level)}
            />
          ))}
        </View>
        <Text style={{ marginBottom: 10 }}>Selected: {experience || "None"}</Text>

      <Button title="Sign Up" onPress={handleRegister} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    padding: 20,
  },
});