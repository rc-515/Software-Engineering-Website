import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert } from 'react-native';
import axios from 'axios';

export default function RegisterScreen({ navigation }) {
  const [form, setForm] = useState({
    full_name: '',
    email: '',
    password: '',
    weight: '',
    height: '',
    bench_press: '',
    experience: ''
  });

  const update = (field, value) => {
    setForm({ ...form, [field]: value });
  };

  const register = async () => {
    try {
      await axios.post("http://YOUR_LOCAL_IP/Software-Engineering-Website/api/register.php", form);
      Alert.alert("Success", "Account created!");
      navigation.navigate("Login");
    } catch (err) {
      Alert.alert("Registration failed", err.response?.data?.error || "Unknown error");
    }
  };

  return (
    <View style={{ padding: 20 }}>
      {["full_name", "email", "password", "weight", "height", "bench_press", "experience"].map((field) => (
        <TextInput
          key={field}
          placeholder={field.replace('_', ' ')}
          secureTextEntry={field === "password"}
          onChangeText={(value) => update(field, value)}
          style={{ marginBottom: 10 }}
        />
      ))}
      <Button title="Register" onPress={register} />
    </View>
  );
}
