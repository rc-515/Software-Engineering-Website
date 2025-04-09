import React, { useState } from 'react';
import { View, TextInput, Button, Alert } from 'react-native';
import { updateMatch } from '../services/api';

export default function MatchEditScreen({ route, navigation }) {
  const matchId = route.params?.matchId;
  const [newDate, setNewDate] = useState('');

  const update = async () => {
    try {
      await updateMatch(matchId, { fight_date: newDate });
      Alert.alert("Updated!");
      navigation.navigate("Dashboard");
    } catch (err) {
      Alert.alert("Error", err.response?.data?.error || "Update failed");
    }
  };

  return (
    <View style={{ padding: 20 }}>
      <TextInput placeholder="New Date (YYYY-MM-DD)" value={newDate} onChangeText={setNewDate} />
      <Button title="Update Match" onPress={update} />
    </View>
  );
}
