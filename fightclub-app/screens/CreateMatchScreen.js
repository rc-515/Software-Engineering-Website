import React, { useState } from 'react';
import { View, TextInput, Button, Text, Alert } from 'react-native';
import { createMatch } from '../services/api';

export default function CreateMatchScreen({ route, navigation }) {
  const challenger = route.params?.user?.email;
  const [opponent, setOpponent] = useState('');
  const [date, setDate] = useState('');

  const isValidDate = (dateString) => {
    // Match format YYYY-MM-DD
    const regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(dateString)) return false;

    // Check if it's a real date
    const dateObj = new Date(dateString);
    return !isNaN(dateObj.getTime());
  };

  const create = async () => {
    const trimmedOpponent = opponent.trim().toLowerCase();
    const trimmedChallenger = challenger?.trim().toLowerCase();
  
    if (!trimmedOpponent) {
      Alert.alert("Missing Field", "Please enter opponent email.");
      return;
    }
  
    // ✅ Check if it's a valid email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(trimmedOpponent)) {
      Alert.alert("Invalid Email", "Please enter a valid opponent email address.");
      return;
    }
  
    if (!date.trim()) {
      Alert.alert("Missing Field", "Please enter a fight date.");
      return;
    }
  
    if (trimmedOpponent === trimmedChallenger) {
      Alert.alert("Invalid Match", "You can't fight yourself!");
      return;
    }
  
    const isValidDate = /^\d{4}-\d{2}-\d{2}$/.test(date) && !isNaN(new Date(date).getTime());
    if (!isValidDate) {
      Alert.alert("Invalid Date", "Please enter a valid date in YYYY-MM-DD format.");
      return;
    }
  
    try {
      await createMatch({
        challenger_name: trimmedChallenger,
        opponent_name: trimmedOpponent,
        fight_date: date
      });
  
      Alert.alert("Success", "Match created");
      navigation.navigate("Dashboard", { user: { email: challenger } });
    } catch (err) {
      console.log("Create match error:", JSON.stringify(err, null, 2));
      Alert.alert("Error", err.response?.data?.error || "Something went wrong");
    }
  };
  
  

  return (
    <View style={{ padding: 20 }}>
      <Text>Opponent Email:</Text>
      <TextInput
        value={opponent}
        onChangeText={setOpponent}
        placeholder="Enter opponent's email"
        style={{ borderWidth: 1, marginBottom: 10, padding: 8 }}
      />

      <Text>Date (YYYY-MM-DD):</Text>
      <TextInput
        value={date}
        onChangeText={setDate}
        placeholder="Enter date"
        style={{ borderWidth: 1, marginBottom: 20, padding: 8 }}
      />

      <Button title="Schedule Match" onPress={create} />
    </View>
  );
}
